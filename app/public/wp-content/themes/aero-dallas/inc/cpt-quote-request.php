<?php
/**
 * Private "Quote Requests" post type that stores form submissions.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the quote_request post type.
 */
function aerodallas_register_quote_request() {
	register_post_type(
		'quote_request',
		array(
			'labels'              => array(
				'name'               => __( 'Quote Requests', 'aero-dallas' ),
				'singular_name'      => __( 'Quote Request', 'aero-dallas' ),
				'menu_name'          => __( 'Quote Requests', 'aero-dallas' ),
				'edit_item'          => __( 'Quote Request', 'aero-dallas' ),
				'search_items'       => __( 'Search Quote Requests', 'aero-dallas' ),
				'not_found'          => __( 'No quote requests yet.', 'aero-dallas' ),
				'not_found_in_trash' => __( 'No quote requests in Trash.', 'aero-dallas' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'show_in_rest'        => false,
			'menu_position'       => 22,
			'menu_icon'           => 'dashicons-email-alt',
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts'       => 'do_not_allow',
				'edit_posts'         => 'edit_others_posts',
				'edit_others_posts'  => 'edit_others_posts',
				'edit_private_posts' => 'edit_others_posts',
				'read_private_posts' => 'edit_others_posts',
				'delete_posts'       => 'delete_others_posts',
			),
			'map_meta_cap'        => true,
			'supports'            => array( 'title' ),
			'rewrite'             => false,
			'query_var'           => false,
		)
	);
}
add_action( 'init', 'aerodallas_register_quote_request' );

/**
 * Field labels stored on each request, keyed by meta suffix.
 *
 * @return array<string,string>
 */
function aerodallas_quote_meta_labels() {
	return array(
		'name'        => __( 'Full Name', 'aero-dallas' ),
		'company'     => __( 'Company Name', 'aero-dallas' ),
		'email'       => __( 'Email Address', 'aero-dallas' ),
		'phone'       => __( 'Phone Number', 'aero-dallas' ),
		'service'     => __( 'Service Required', 'aero-dallas' ),
		'equipment'   => __( 'Equipment Type', 'aero-dallas' ),
		'description' => __( 'Project Description', 'aero-dallas' ),
	);
}

/**
 * List table columns.
 *
 * @param string[] $columns Columns.
 * @return string[]
 */
function aerodallas_quote_columns( $columns ) {
	return array(
		'cb'      => $columns['cb'],
		'title'   => __( 'Request', 'aero-dallas' ),
		'company' => __( 'Company', 'aero-dallas' ),
		'contact' => __( 'Contact', 'aero-dallas' ),
		'service' => __( 'Service', 'aero-dallas' ),
		'file'    => __( 'File', 'aero-dallas' ),
		'date'    => __( 'Received', 'aero-dallas' ),
	);
}
add_filter( 'manage_quote_request_posts_columns', 'aerodallas_quote_columns' );

/**
 * List table column content.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function aerodallas_quote_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'company':
			echo esc_html( get_post_meta( $post_id, '_quote_company', true ) );
			break;
		case 'contact':
			$email = get_post_meta( $post_id, '_quote_email', true );
			$phone = get_post_meta( $post_id, '_quote_phone', true );
			if ( $email ) {
				printf( '<a href="%1$s">%2$s</a><br>', esc_url( 'mailto:' . $email ), esc_html( $email ) );
			}
			echo esc_html( $phone );
			break;
		case 'service':
			echo esc_html( get_post_meta( $post_id, '_quote_service', true ) );
			break;
		case 'file':
			$file_id = (int) get_post_meta( $post_id, '_quote_file_id', true );
			if ( $file_id ) {
				printf( '<a href="%1$s">%2$s</a>', esc_url( wp_get_attachment_url( $file_id ) ), esc_html__( 'Download', 'aero-dallas' ) );
			} else {
				echo '—';
			}
			break;
	}
}
add_action( 'manage_quote_request_posts_custom_column', 'aerodallas_quote_column_content', 10, 2 );

/**
 * Read-only details meta box on the edit screen.
 */
function aerodallas_quote_meta_box() {
	add_meta_box( 'aerodallas-quote-details', __( 'Request details', 'aero-dallas' ), 'aerodallas_quote_details_box', 'quote_request', 'normal', 'high' );
	remove_meta_box( 'slugdiv', 'quote_request', 'normal' );
}
add_action( 'add_meta_boxes_quote_request', 'aerodallas_quote_meta_box' );

/**
 * Render request details.
 *
 * @param WP_Post $post Request.
 */
function aerodallas_quote_details_box( $post ) {
	echo '<table class="form-table aerodallas-quote-table"><tbody>';
	foreach ( aerodallas_quote_meta_labels() as $key => $label ) {
		$value = (string) get_post_meta( $post->ID, '_quote_' . $key, true );
		if ( 'email' === $key && $value ) {
			$value_html = sprintf( '<a href="%1$s">%2$s</a>', esc_url( 'mailto:' . $value ), esc_html( $value ) );
		} elseif ( 'phone' === $key && $value ) {
			$value_html = sprintf( '<a href="%1$s">%2$s</a>', esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $value ) ), esc_html( $value ) );
		} else {
			$value_html = '' === $value ? '—' : nl2br( esc_html( $value ) );
		}
		printf( '<tr><th scope="row">%1$s</th><td>%2$s</td></tr>', esc_html( $label ), $value_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	}
	$file_id = (int) get_post_meta( $post->ID, '_quote_file_id', true );
	printf(
		'<tr><th scope="row">%1$s</th><td>%2$s</td></tr>',
		esc_html__( 'Specifications file', 'aero-dallas' ),
		$file_id ? sprintf( '<a href="%1$s">%2$s</a>', esc_url( wp_get_attachment_url( $file_id ) ), esc_html( basename( (string) get_attached_file( $file_id ) ) ) ) : '—' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	);
	printf(
		'<tr><th scope="row">%1$s</th><td>%2$s</td></tr>',
		esc_html__( 'Submitted from', 'aero-dallas' ),
		esc_html( (string) get_post_meta( $post->ID, '_quote_source', true ) )
	);
	echo '</tbody></table>';
}

/**
 * Remove the "Add New" button and quick edit for requests.
 *
 * @param array   $actions Row actions.
 * @param WP_Post $post    Post.
 * @return array
 */
function aerodallas_quote_row_actions( $actions, $post ) {
	if ( 'quote_request' === $post->post_type ) {
		unset( $actions['inline hide-if-no-js'] );
	}
	return $actions;
}
add_filter( 'post_row_actions', 'aerodallas_quote_row_actions', 10, 2 );

/**
 * Show a count of new (unread) requests in the menu.
 */
function aerodallas_quote_menu_badge() {
	global $menu;
	if ( ! current_user_can( 'edit_others_posts' ) || ! is_array( $menu ) ) {
		return;
	}
	$unread = (int) ( new WP_Query(
		array(
			'post_type'      => 'quote_request',
			'post_status'    => 'private',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_quote_unread', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- small admin-only query.
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	) )->found_posts;
	if ( ! $unread ) {
		return;
	}
	foreach ( $menu as $i => $item ) {
		if ( 'edit.php?post_type=quote_request' === $item[2] ) {
			$menu[ $i ][0] .= ' <span class="awaiting-mod"><span class="pending-count">' . (int) $unread . '</span></span>'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- adding a badge.
		}
	}
}
add_action( 'admin_menu', 'aerodallas_quote_menu_badge', 99 );

/**
 * Mark a request as read when it is opened.
 */
function aerodallas_quote_mark_read() {
	$screen = get_current_screen();
	if ( $screen && 'quote_request' === $screen->post_type && 'post' === $screen->base && isset( $_GET['post'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only state change on view.
		$post_id = absint( $_GET['post'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( current_user_can( 'edit_post', $post_id ) ) {
			delete_post_meta( $post_id, '_quote_unread' );
		}
	}
}
add_action( 'current_screen', 'aerodallas_quote_mark_read' );
