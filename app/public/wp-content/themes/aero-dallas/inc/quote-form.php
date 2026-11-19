<?php
/**
 * Request a Quote form: handler, validation, spam checks, storage and email.
 *
 * Submissions go to admin-post.php. With JavaScript the form is sent with
 * fetch() and receives JSON; without it, the browser is redirected back to
 * the form with the result.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

define( 'AERODALLAS_QUOTE_MAX_BYTES', 10 * MB_IN_BYTES );
define( 'AERODALLAS_QUOTE_MIN_SECONDS', 3 );
define( 'AERODALLAS_QUOTE_RATE_LIMIT', 5 );

/**
 * File types accepted for the specifications upload.
 *
 * @return array<string,string> ext pattern => mime.
 */
function aerodallas_quote_mimes() {
	return array(
		'pdf'      => 'application/pdf',
		'doc'      => 'application/msword',
		'docx'     => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xls'      => 'application/vnd.ms-excel',
		'xlsx'     => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'jpg|jpeg' => 'image/jpeg',
		'png'      => 'image/png',
	);
}

/**
 * Signed timestamp for the time-trap field.
 *
 * @return string
 */
function aerodallas_quote_timestamp() {
	$time = time();
	return $time . '.' . hash_hmac( 'sha256', (string) $time, wp_salt( 'nonce' ) );
}

/**
 * Validate the signed timestamp.
 *
 * @param string $value Field value.
 * @return string Empty when fine, otherwise an error message.
 */
function aerodallas_quote_check_timestamp( $value ) {
	$parts = explode( '.', $value );
	if ( 2 !== count( $parts ) || ! hash_equals( hash_hmac( 'sha256', $parts[0], wp_salt( 'nonce' ) ), $parts[1] ) ) {
		return __( 'This form has expired. Please reload the page and try again.', 'aero-dallas' );
	}
	$elapsed = time() - (int) $parts[0];
	if ( $elapsed < AERODALLAS_QUOTE_MIN_SECONDS ) {
		return __( 'That was very quick. Please wait a few seconds and submit again.', 'aero-dallas' );
	}
	if ( $elapsed > DAY_IN_SECONDS ) {
		return __( 'This form has expired. Please reload the page and try again.', 'aero-dallas' );
	}
	return '';
}

/**
 * Per-visitor rate limit: at most AERODALLAS_QUOTE_RATE_LIMIT submissions in 10 minutes.
 *
 * @param bool $increment Record this attempt.
 * @return bool True when the visitor is over the limit.
 */
function aerodallas_quote_rate_limited( $increment = false ) {
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'aerodallas_qrl_' . md5( $ip . wp_salt( 'auth' ) );
	$hits = (int) get_transient( $key );
	if ( $increment ) {
		set_transient( $key, $hits + 1, 10 * MINUTE_IN_SECONDS );
	}
	return $hits >= AERODALLAS_QUOTE_RATE_LIMIT;
}

/**
 * Handle a submission.
 */
function aerodallas_quote_handle() {
	$is_ajax = ! empty( $_POST['aq_ajax'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- only selects the response format; nonce checked below.
	$return  = isset( $_POST['aq_return'] ) ? esc_url_raw( wp_unslash( $_POST['aq_return'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$return  = wp_validate_redirect( $return, aerodallas_page_url( 'contact' ) );
	$return  = remove_query_arg( array( 'quote', 'quote_error' ), $return );

	$values = array();
	$errors = array();

	if ( ! isset( $_POST['aq_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['aq_nonce'] ), 'aerodallas_quote' ) ) {
		$errors['form'] = __( 'Your session has expired. Please reload the page and submit again.', 'aero-dallas' );
		aerodallas_quote_respond( false, $errors, $values, $is_ajax, $return );
	}

	// Honeypot: real visitors never see or fill this field. Pretend success.
	if ( ! empty( $_POST['aq_website'] ) ) {
		aerodallas_quote_respond( true, array(), array(), $is_ajax, $return );
	}

	$time_error = aerodallas_quote_check_timestamp( isset( $_POST['aq_ts'] ) ? sanitize_text_field( wp_unslash( $_POST['aq_ts'] ) ) : '' );
	if ( $time_error ) {
		$errors['form'] = $time_error;
	}

	if ( aerodallas_quote_rate_limited() ) {
		$errors['form'] = __( 'We have received several requests from you in a short time. Please wait a few minutes, or call us directly.', 'aero-dallas' );
	}

	$services = aerodallas_service_options();

	$values = array(
		'name'        => isset( $_POST['aq_name'] ) ? sanitize_text_field( wp_unslash( $_POST['aq_name'] ) ) : '',
		'company'     => isset( $_POST['aq_company'] ) ? sanitize_text_field( wp_unslash( $_POST['aq_company'] ) ) : '',
		'email'       => isset( $_POST['aq_email'] ) ? sanitize_email( wp_unslash( $_POST['aq_email'] ) ) : '',
		'phone'       => isset( $_POST['aq_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['aq_phone'] ) ) : '',
		'service'     => isset( $_POST['aq_service'] ) ? sanitize_key( wp_unslash( $_POST['aq_service'] ) ) : '',
		'equipment'   => isset( $_POST['aq_equipment'] ) ? sanitize_text_field( wp_unslash( $_POST['aq_equipment'] ) ) : '',
		'description' => isset( $_POST['aq_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['aq_description'] ) ) : '',
	);
	$raw_email = isset( $_POST['aq_email'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['aq_email'] ) ) ) : '';

	if ( '' === $values['name'] ) {
		$errors['name'] = __( 'Please enter your full name.', 'aero-dallas' );
	} elseif ( mb_strlen( $values['name'] ) > 120 ) {
		$errors['name'] = __( 'Please shorten your name to 120 characters or fewer.', 'aero-dallas' );
	}
	if ( '' === $raw_email ) {
		$errors['email'] = __( 'Please enter your email address.', 'aero-dallas' );
	} elseif ( ! is_email( $values['email'] ) ) {
		$errors['email'] = __( 'Please enter a valid email address, for example name@company.com.', 'aero-dallas' );
		$values['email'] = $raw_email;
	}
	if ( '' === $values['phone'] ) {
		$errors['phone'] = __( 'Please enter a phone number.', 'aero-dallas' );
	} elseif ( ! preg_match( '/^[\d\s()+.\-\/x]{6,30}$/i', $values['phone'] ) || preg_match_all( '/\d/', $values['phone'] ) < 6 ) {
		$errors['phone'] = __( 'Please enter a valid phone number, including the country code if outside the US.', 'aero-dallas' );
	}
	if ( ! isset( $services[ $values['service'] ] ) ) {
		$errors['service'] = __( 'Please choose the service you need.', 'aero-dallas' );
	}
	if ( '' === $values['description'] ) {
		$errors['description'] = __( 'Please describe your project or requirements.', 'aero-dallas' );
	} elseif ( mb_strlen( $values['description'] ) > 5000 ) {
		$errors['description'] = __( 'Please shorten the description to 5,000 characters or fewer, or attach a file.', 'aero-dallas' );
	}

	$has_file   = isset( $_FILES['aq_specs'] ) && is_array( $_FILES['aq_specs'] ) && UPLOAD_ERR_NO_FILE !== (int) ( $_FILES['aq_specs']['error'] ?? UPLOAD_ERR_NO_FILE ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
	$file_error = $has_file ? aerodallas_quote_check_file( $_FILES['aq_specs'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- validated in aerodallas_quote_check_file().
	if ( $file_error ) {
		$errors['specs'] = $file_error;
	}

	if ( $errors ) {
		aerodallas_quote_respond( false, $errors, $values, $is_ajax, $return );
	}

	aerodallas_quote_rate_limited( true );

	$service_label = $services[ $values['service'] ];
	$post_id       = wp_insert_post(
		array(
			'post_type'   => 'quote_request',
			'post_status' => 'private',
			'post_title'  => wp_strip_all_tags( implode( ' — ', array_filter( array( $values['name'], $values['company'], $service_label ) ) ) ),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		$errors['form'] = __( 'Sorry, something went wrong while saving your request. Please try again or call us.', 'aero-dallas' );
		aerodallas_quote_respond( false, $errors, $values, $is_ajax, $return );
	}

	foreach ( $values as $key => $value ) {
		update_post_meta( $post_id, '_quote_' . $key, 'service' === $key ? $service_label : $value );
	}
	update_post_meta( $post_id, '_quote_source', $return );
	update_post_meta( $post_id, '_quote_unread', '1' );

	$file_id = 0;
	if ( $has_file ) {
		$file_id = aerodallas_quote_store_file( $post_id );
		if ( is_wp_error( $file_id ) ) {
			update_post_meta( $post_id, '_quote_file_error', $file_id->get_error_message() );
			$file_id = 0;
		} else {
			update_post_meta( $post_id, '_quote_file_id', $file_id );
		}
	}

	$sent = aerodallas_quote_send_email( $post_id, $values, $service_label, $file_id );
	if ( ! $sent ) {
		update_post_meta( $post_id, '_quote_mail_failed', '1' );
	}

	/**
	 * Fires after a quote request is stored.
	 *
	 * @param int   $post_id Quote request ID.
	 * @param array $values  Sanitised values.
	 */
	do_action( 'aerodallas_quote_received', $post_id, $values );

	aerodallas_quote_respond( true, array(), array(), $is_ajax, $return );
}
add_action( 'admin_post_nopriv_aerodallas_quote', 'aerodallas_quote_handle' );
add_action( 'admin_post_aerodallas_quote', 'aerodallas_quote_handle' );

/**
 * Validate an uploaded file array.
 *
 * @param array $file Entry from $_FILES.
 * @return string Error message or empty string.
 */
function aerodallas_quote_check_file( $file ) {
	$error = (int) ( $file['error'] ?? UPLOAD_ERR_NO_FILE );
	if ( UPLOAD_ERR_INI_SIZE === $error || UPLOAD_ERR_FORM_SIZE === $error ) {
		return __( 'This file is larger than 10 MB. Please choose a smaller file.', 'aero-dallas' );
	}
	if ( UPLOAD_ERR_OK !== $error || empty( $file['tmp_name'] ) || ! is_uploaded_file( $file['tmp_name'] ) ) {
		return __( 'The file could not be uploaded. Please try again.', 'aero-dallas' );
	}
	if ( (int) $file['size'] > AERODALLAS_QUOTE_MAX_BYTES ) {
		return __( 'This file is larger than 10 MB. Please choose a smaller file.', 'aero-dallas' );
	}
	$check = wp_check_filetype_and_ext( $file['tmp_name'], sanitize_file_name( (string) $file['name'] ), aerodallas_quote_mimes() );
	if ( empty( $check['ext'] ) || empty( $check['type'] ) ) {
		return __( 'Please upload a PDF, Word (DOC, DOCX), Excel (XLS, XLSX), JPG or PNG file.', 'aero-dallas' );
	}
	return '';
}

/**
 * Store the uploaded file through the media library, attached to the request.
 * The file name gets a random prefix so its URL cannot be guessed.
 *
 * @param int $post_id Quote request ID.
 * @return int|WP_Error Attachment ID.
 */
function aerodallas_quote_store_file( $post_id ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$prefix = static function ( $file ) {
		$file['name'] = wp_generate_password( 12, false ) . '-' . $file['name'];
		return $file;
	};
	add_filter( 'wp_handle_upload_prefilter', $prefix );
	$id = media_handle_upload(
		'aq_specs',
		$post_id,
		array( 'post_title' => get_the_title( $post_id ) . ' — ' . __( 'specifications', 'aero-dallas' ) ),
		array(
			'test_form' => false,
			'mimes'     => aerodallas_quote_mimes(),
		)
	);
	remove_filter( 'wp_handle_upload_prefilter', $prefix );
	return $id;
}

/**
 * Email the request to the notification address: the branded HTML email
 * (template-parts/email-quote.php) with a plain-text version for clients
 * that do not show HTML.
 *
 * @param int    $post_id       Request ID.
 * @param array  $values        Values.
 * @param string $service_label Service label.
 * @param int    $file_id       Attachment ID or 0.
 * @return bool
 */
function aerodallas_quote_send_email( $post_id, $values, $service_label, $file_id ) {
	$edit_url = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
	$labels   = aerodallas_quote_meta_labels();
	$lines  = array();
	foreach ( $values as $key => $value ) {
		if ( 'service' === $key ) {
			$value = $service_label;
		}
		if ( 'description' === $key ) {
			$lines[] = '';
			$lines[] = $labels[ $key ] . ':';
			$lines[] = $value;
			continue;
		}
		$lines[] = $labels[ $key ] . ': ' . ( '' === $value ? '—' : $value );
	}
	$attachments = array();
	$file        = array();
	if ( $file_id ) {
		$lines[]  = '';
		$lines[]  = __( 'Specifications file:', 'aero-dallas' ) . ' ' . wp_get_attachment_url( $file_id );
		$path     = get_attached_file( $file_id );
		$exists   = $path && file_exists( $path );
		if ( $exists ) {
			$attachments[] = $path;
		}
		$file = array(
			// Without the random prefix added in aerodallas_quote_store_file().
			'name'     => preg_replace( '/^[A-Za-z0-9]{12}-/', '', wp_basename( (string) $path ) ),
			'url'      => wp_get_attachment_url( $file_id ),
			'size'     => $exists ? size_format( filesize( $path ) ) : '',
			'type'     => strtoupper( (string) pathinfo( (string) $path, PATHINFO_EXTENSION ) ),
			'attached' => $exists,
		);
	}
	$lines[] = '';
	$lines[] = __( 'View in WordPress:', 'aero-dallas' ) . ' ' . $edit_url;

	ob_start();
	get_template_part(
		'template-parts/email-quote',
		null,
		array(
			'post_id'    => $post_id,
			'values'     => $values,
			'service'    => $service_label,
			'file'       => $file,
			'file_error' => (string) get_post_meta( $post_id, '_quote_file_error', true ),
			'edit_url'   => $edit_url,
			'source'     => (string) get_post_meta( $post_id, '_quote_source', true ),
			'received'   => (int) get_post_time( 'U', true, $post_id ),
		)
	);
	$html = (string) ob_get_clean();

	$subject = sprintf(
		/* translators: 1: service, 2: name. */
		__( 'New quote request: %1$s — %2$s', 'aero-dallas' ),
		$service_label,
		$values['name']
	);
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	if ( is_email( $values['email'] ) ) {
		$headers[] = sprintf( 'Reply-To: %s <%s>', str_replace( array( '<', '>', '"', "\r", "\n" ), '', $values['name'] ), $values['email'] );
	}

	// Plain-text part for clients that do not show HTML.
	$text     = implode( "\n", $lines );
	$add_text = static function ( $phpmailer ) use ( $text ) {
		$phpmailer->AltBody = $text; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- PHPMailer property.
	};
	add_action( 'phpmailer_init', $add_text );
	$sent = wp_mail( aerodallas_quote_recipient(), wp_specialchars_decode( $subject ), $html, $headers, $attachments );
	remove_action( 'phpmailer_init', $add_text );

	return $sent;
}

/**
 * Send the response (JSON or redirect) and exit.
 *
 * @param bool   $success Whether the request was accepted.
 * @param array  $errors  Field => message.
 * @param array  $values  Submitted values to refill the form.
 * @param bool   $is_ajax Respond with JSON.
 * @param string $return  Page to redirect to.
 */
function aerodallas_quote_respond( $success, $errors, $values, $is_ajax, $return ) {
	if ( $is_ajax ) {
		wp_send_json(
			array(
				'success' => $success,
				'message' => $success ? aerodallas_quote_success_message() : aerodallas_quote_error_summary( $errors ),
				'errors'  => $errors,
			),
			200
		);
	}

	if ( $success ) {
		wp_safe_redirect( add_query_arg( 'quote', 'sent', $return ) . '#quote' );
		exit;
	}

	$token = wp_generate_password( 20, false );
	set_transient(
		'aerodallas_qf_' . $token,
		array(
			'errors' => $errors,
			'values' => $values,
		),
		10 * MINUTE_IN_SECONDS
	);
	wp_safe_redirect( add_query_arg( 'quote_error', $token, $return ) . '#quote' );
	exit;
}

/**
 * Message shown after a successful submission.
 *
 * @return string
 */
function aerodallas_quote_success_message() {
	return __( 'Thank you. Your request has been received. Our team will review your requirements and contact you to discuss the next steps.', 'aero-dallas' );
}

/**
 * Summary line above the form when there are errors.
 *
 * @param array $errors Errors.
 * @return string
 */
function aerodallas_quote_error_summary( $errors ) {
	if ( isset( $errors['form'] ) && 1 === count( $errors ) ) {
		return $errors['form'];
	}
	$count = count( array_diff_key( $errors, array( 'form' => true ) ) );
	return sprintf(
		/* translators: %d: number of fields with problems. */
		_n( 'Please correct %d field below and submit again.', 'Please correct %d fields below and submit again.', $count, 'aero-dallas' ),
		$count
	);
}

/**
 * State for rendering the form: errors and values after a no-JS round trip,
 * success flag, and the pre-selected service.
 *
 * @return array{sent:bool,errors:array,values:array}
 */
function aerodallas_quote_state() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only display state.
	$state = array(
		'sent'   => isset( $_GET['quote'] ) && 'sent' === $_GET['quote'],
		'errors' => array(),
		'values' => array(),
	);
	if ( isset( $_GET['quote_error'] ) ) {
		$token = preg_replace( '/[^A-Za-z0-9]/', '', sanitize_text_field( wp_unslash( $_GET['quote_error'] ) ) );
		$saved = $token ? get_transient( 'aerodallas_qf_' . $token ) : false;
		if ( is_array( $saved ) ) {
			$state['errors'] = (array) $saved['errors'];
			$state['values'] = (array) $saved['values'];
			delete_transient( 'aerodallas_qf_' . $token );
		}
	}
	if ( empty( $state['values']['service'] ) && isset( $_GET['service'] ) ) {
		$state['values']['service'] = sanitize_key( wp_unslash( $_GET['service'] ) );
	}
	foreach ( array( 'equipment', 'company' ) as $prefill ) {
		if ( empty( $state['values'][ $prefill ] ) && isset( $_GET[ $prefill ] ) ) {
			$state['values'][ $prefill ] = sanitize_text_field( wp_unslash( $_GET[ $prefill ] ) );
		}
	}
	// phpcs:enable
	return $state;
}

/**
 * Keep the error and success pages out of search indexes.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function aerodallas_quote_noindex( $robots ) {
	if ( isset( $_GET['quote'] ) || isset( $_GET['quote_error'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$robots['noindex'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'aerodallas_quote_noindex' );
