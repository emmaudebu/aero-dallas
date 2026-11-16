<?php
/**
 * Request a Quote form markup.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_state    = aerodallas_quote_state();
$aerodallas_errors   = $aerodallas_state['errors'];
$aerodallas_values   = $aerodallas_state['values'];
$aerodallas_services = aerodallas_service_options();
$aerodallas_return   = get_permalink();

/**
 * Attributes for an input: aria-invalid and aria-describedby when it has an error.
 *
 * @param string $key    Field key.
 * @param array  $errors Errors.
 * @param string $hint   Optional hint element ID.
 * @return string
 */
$aerodallas_aria = static function ( $key, $errors, $hint = '' ) {
	$ids = array_filter( array( $hint, isset( $errors[ $key ] ) ? 'aq-' . $key . '-error' : '' ) );
	$out = isset( $errors[ $key ] ) ? ' aria-invalid="true"' : '';
	return $ids ? $out . ' aria-describedby="' . esc_attr( implode( ' ', $ids ) ) . '"' : $out;
};

/**
 * Error message element (always present so JS can fill it).
 *
 * @param string $key    Field key.
 * @param array  $errors Errors.
 */
$aerodallas_error = static function ( $key, $errors ) {
	printf(
		'<p class="field__error" id="aq-%1$s-error"%3$s>%2$s</p>',
		esc_attr( $key ),
		isset( $errors[ $key ] ) ? aerodallas_icon( 'alert' ) . esc_html( $errors[ $key ] ) : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- icon is static markup.
		isset( $errors[ $key ] ) ? '' : ' hidden'
	);
};

$aerodallas_value = static function ( $key ) use ( $aerodallas_values ) {
	return isset( $aerodallas_values[ $key ] ) ? (string) $aerodallas_values[ $key ] : '';
};
?>

<div class="form-status form-status--success" id="quote-success" tabindex="-1" role="status"<?php echo $aerodallas_state['sent'] ? '' : ' hidden'; ?>>
	<?php echo aerodallas_icon( 'check-ring' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div>
		<p class="form-status__title"><?php esc_html_e( 'Request received', 'aero-dallas' ); ?></p>
		<p class="form-status__message"><?php echo esc_html( aerodallas_quote_success_message() ); ?></p>
	</div>
</div>

<div class="form-status form-status--error" id="quote-errors" tabindex="-1" role="alert"<?php echo $aerodallas_errors ? '' : ' hidden'; ?>>
	<?php echo aerodallas_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<p class="form-status__message"><?php echo $aerodallas_errors ? esc_html( aerodallas_quote_error_summary( $aerodallas_errors ) ) : ''; ?></p>
</div>

<form class="quote-form" id="quote-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" novalidate<?php echo $aerodallas_state['sent'] ? ' hidden' : ''; ?>>
	<input type="hidden" name="action" value="aerodallas_quote">
	<input type="hidden" name="aq_return" value="<?php echo esc_url( $aerodallas_return ); ?>">
	<input type="hidden" name="aq_ts" value="<?php echo esc_attr( aerodallas_quote_timestamp() ); ?>">
	<?php wp_nonce_field( 'aerodallas_quote', 'aq_nonce', false ); ?>

	<div class="field field--hp" aria-hidden="true">
		<label for="aq-website"><?php esc_html_e( 'Leave this field empty', 'aero-dallas' ); ?></label>
		<input type="text" id="aq-website" name="aq_website" tabindex="-1" autocomplete="off">
	</div>

	<div class="form-grid">
		<div class="field<?php echo isset( $aerodallas_errors['name'] ) ? ' has-error' : ''; ?>">
			<label for="aq-name"><?php esc_html_e( 'Full Name', 'aero-dallas' ); ?> <span class="req" aria-hidden="true">*</span></label>
			<input type="text" id="aq-name" name="aq_name" autocomplete="name" required maxlength="120" value="<?php echo esc_attr( $aerodallas_value( 'name' ) ); ?>"<?php echo $aerodallas_aria( 'name', $aerodallas_errors ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php $aerodallas_error( 'name', $aerodallas_errors ); ?>
		</div>

		<div class="field">
			<label for="aq-company"><?php esc_html_e( 'Company Name', 'aero-dallas' ); ?></label>
			<input type="text" id="aq-company" name="aq_company" autocomplete="organization" maxlength="160" value="<?php echo esc_attr( $aerodallas_value( 'company' ) ); ?>">
		</div>

		<div class="field<?php echo isset( $aerodallas_errors['email'] ) ? ' has-error' : ''; ?>">
			<label for="aq-email"><?php esc_html_e( 'Email Address', 'aero-dallas' ); ?> <span class="req" aria-hidden="true">*</span></label>
			<input type="email" id="aq-email" name="aq_email" autocomplete="email" required maxlength="190" value="<?php echo esc_attr( $aerodallas_value( 'email' ) ); ?>"<?php echo $aerodallas_aria( 'email', $aerodallas_errors ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php $aerodallas_error( 'email', $aerodallas_errors ); ?>
		</div>

		<div class="field<?php echo isset( $aerodallas_errors['phone'] ) ? ' has-error' : ''; ?>">
			<label for="aq-phone"><?php esc_html_e( 'Phone Number', 'aero-dallas' ); ?> <span class="req" aria-hidden="true">*</span></label>
			<input type="tel" id="aq-phone" name="aq_phone" autocomplete="tel" required maxlength="30" value="<?php echo esc_attr( $aerodallas_value( 'phone' ) ); ?>"<?php echo $aerodallas_aria( 'phone', $aerodallas_errors ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php $aerodallas_error( 'phone', $aerodallas_errors ); ?>
		</div>

		<div class="field<?php echo isset( $aerodallas_errors['service'] ) ? ' has-error' : ''; ?>">
			<label for="aq-service"><?php esc_html_e( 'Service Required', 'aero-dallas' ); ?> <span class="req" aria-hidden="true">*</span></label>
			<div class="select">
				<select id="aq-service" name="aq_service" required<?php echo $aerodallas_aria( 'service', $aerodallas_errors ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<option value=""><?php esc_html_e( 'Choose a service', 'aero-dallas' ); ?></option>
					<?php foreach ( $aerodallas_services as $aerodallas_key => $aerodallas_label ) : ?>
						<option value="<?php echo esc_attr( $aerodallas_key ); ?>"<?php selected( $aerodallas_value( 'service' ), $aerodallas_key ); ?>><?php echo esc_html( $aerodallas_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php $aerodallas_error( 'service', $aerodallas_errors ); ?>
		</div>

		<div class="field">
			<label for="aq-equipment"><?php esc_html_e( 'Equipment Type', 'aero-dallas' ); ?></label>
			<input type="text" id="aq-equipment" name="aq_equipment" maxlength="160" value="<?php echo esc_attr( $aerodallas_value( 'equipment' ) ); ?>" aria-describedby="aq-equipment-hint">
			<p class="field__hint" id="aq-equipment-hint"><?php esc_html_e( 'For example: ground power unit, aircraft tow bar, compressor.', 'aero-dallas' ); ?></p>
		</div>

		<div class="field field--full<?php echo isset( $aerodallas_errors['description'] ) ? ' has-error' : ''; ?>">
			<label for="aq-description"><?php esc_html_e( 'Project Description', 'aero-dallas' ); ?> <span class="req" aria-hidden="true">*</span></label>
			<textarea id="aq-description" name="aq_description" rows="6" required maxlength="5000"<?php echo $aerodallas_aria( 'description', $aerodallas_errors, 'aq-description-hint' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_textarea( $aerodallas_value( 'description' ) ); ?></textarea>
			<p class="field__hint" id="aq-description-hint"><?php esc_html_e( 'Describe the work, quantities, timelines and any technical details.', 'aero-dallas' ); ?></p>
			<?php $aerodallas_error( 'description', $aerodallas_errors ); ?>
		</div>

		<div class="field field--full<?php echo isset( $aerodallas_errors['specs'] ) ? ' has-error' : ''; ?>">
			<span class="field__label" id="aq-specs-label"><?php esc_html_e( 'Upload Equipment Specifications', 'aero-dallas' ); ?> <span class="field__optional"><?php esc_html_e( '(optional)', 'aero-dallas' ); ?></span></span>
			<label class="file-drop" for="aq-specs">
				<?php echo aerodallas_icon( 'upload' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="file-drop__text" data-empty="<?php esc_attr_e( 'Choose a file', 'aero-dallas' ); ?>"><?php esc_html_e( 'Choose a file', 'aero-dallas' ); ?></span>
				<span class="file-drop__hint" id="aq-specs-hint"><?php esc_html_e( 'PDF, DOC, DOCX, XLS, XLSX, JPG or PNG. Max 10 MB.', 'aero-dallas' ); ?></span>
			</label>
			<input class="file-input" type="file" id="aq-specs" name="aq_specs" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png" aria-labelledby="aq-specs-label"<?php echo $aerodallas_aria( 'specs', $aerodallas_errors, 'aq-specs-hint' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php $aerodallas_error( 'specs', $aerodallas_errors ); ?>
		</div>
	</div>

	<div class="quote-form__submit">
		<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Submit Request', 'aero-dallas' ); ?></button>
		<p class="quote-form__consent">
			<?php
			printf(
				/* translators: %s: link to the privacy policy. */
				esc_html__( 'By submitting this form you agree that we may use your details to respond to your request, as described in our %s.', 'aero-dallas' ),
				'<a href="' . esc_url( aerodallas_page_url( 'policies', 'privacy' ) ) . '">' . esc_html__( 'Privacy Policy', 'aero-dallas' ) . '</a>'
			);
			?>
		</p>
	</div>
</form>
