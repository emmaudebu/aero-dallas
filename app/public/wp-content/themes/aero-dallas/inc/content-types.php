<?php
/**
 * Editable repeatable content: services, process steps, core values,
 * "why choose" points and FAQs. Each is a non-public post type grouped
 * under one "Site Content" admin menu and ordered by the Order field.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Definitions of the theme's content types.
 *
 * @return array<string,array>
 */
function aerodallas_content_types() {
	return array(
		'aerodallas_service' => array(
			'plural'   => __( 'Services', 'aero-dallas' ),
			'singular' => __( 'Service', 'aero-dallas' ),
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'help'     => __( 'The four services. Each has its own page at /services/<slug>/. Title = service name. Excerpt = short text on the home page card. Content = the detailed write-up on the service page. "Service details" box = hero intro, the "What\'s included" list and photos. Featured image replaces the hero photo.', 'aero-dallas' ),
		),
		'aerodallas_step'    => array(
			'plural'   => __( 'Process Steps', 'aero-dallas' ),
			'singular' => __( 'Process Step', 'aero-dallas' ),
			'supports' => array( 'title', 'excerpt', 'page-attributes' ),
			'help'     => __( 'The numbered steps on How We Work and the home page. Title = step name, Excerpt = description. Numbering follows the Order field.', 'aero-dallas' ),
		),
		'aerodallas_value'   => array(
			'plural'   => __( 'Core Values', 'aero-dallas' ),
			'singular' => __( 'Core Value', 'aero-dallas' ),
			'supports' => array( 'title', 'excerpt', 'page-attributes' ),
			'help'     => __( 'The core values on the About page. Title = value, Excerpt = description.', 'aero-dallas' ),
		),
		'aerodallas_reason'  => array(
			'plural'   => __( 'Why Choose Points', 'aero-dallas' ),
			'singular' => __( 'Why Choose Point', 'aero-dallas' ),
			'supports' => array( 'title', 'page-attributes' ),
			'help'     => __( 'The "Why Choose AERO DALLAS INDUSTRIAL?" list on the home and About pages. Title = the point.', 'aero-dallas' ),
		),
		'aerodallas_testimonial' => array(
			'plural'   => __( 'Testimonials', 'aero-dallas' ),
			'singular' => __( 'Testimonial', 'aero-dallas' ),
			'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'help'     => __( 'Client testimonials for the home page. Only add real, approved quotes. Title = client name, Content = the quote, Featured image = optional photo. The section stays hidden until at least one is published.', 'aero-dallas' ),
		),
		'aerodallas_faq'     => array(
			'plural'   => __( 'FAQs', 'aero-dallas' ),
			'singular' => __( 'FAQ', 'aero-dallas' ),
			'supports' => array( 'title', 'editor', 'page-attributes' ),
			'help'     => __( 'Frequently asked questions on the home and Contact pages. Title = question, Content = answer.', 'aero-dallas' ),
		),
	);
}

/**
 * Register the content types.
 */
function aerodallas_register_content_types() {
	foreach ( aerodallas_content_types() as $type => $def ) {
		$is_service = 'aerodallas_service' === $type;
		register_post_type(
			$type,
			array(
				'labels'              => array(
					'name'               => $def['plural'],
					'singular_name'      => $def['singular'],
					'menu_name'          => $def['plural'],
					'all_items'          => $def['plural'],
					/* translators: %s: content type name, singular. */
					'add_new_item'       => sprintf( __( 'Add %s', 'aero-dallas' ), $def['singular'] ),
					/* translators: %s: content type name, singular. */
					'edit_item'          => sprintf( __( 'Edit %s', 'aero-dallas' ), $def['singular'] ),
					/* translators: %s: content type name, plural. */
					'not_found'          => sprintf( __( 'No %s found.', 'aero-dallas' ), strtolower( $def['plural'] ) ),
					'not_found_in_trash' => __( 'Nothing found in Trash.', 'aero-dallas' ),
				),
				'public'              => $is_service,
				'publicly_queryable'  => $is_service,
				'exclude_from_search' => ! $is_service,
				'show_ui'             => true,
				'show_in_menu'        => 'aerodallas-content',
				'show_in_nav_menus'   => $is_service,
				'show_in_rest'        => $is_service,
				'hierarchical'        => false,
				'capability_type'     => 'page',
				'map_meta_cap'        => true,
				'supports'            => $def['supports'],
				'rewrite'             => $is_service ? array( 'slug' => 'services', 'with_front' => false ) : false,
				'query_var'           => $is_service,
				'has_archive'         => false,
			)
		);
	}
}
add_action( 'init', 'aerodallas_register_content_types' );

/**
 * Top-level "Site Content" admin menu with a short guide page.
 */
function aerodallas_content_menu() {
	add_menu_page(
		__( 'Site Content', 'aero-dallas' ),
		__( 'Site Content', 'aero-dallas' ),
		'edit_pages',
		'aerodallas-content',
		'aerodallas_content_overview',
		'dashicons-screenoptions',
		21
	);
	add_submenu_page(
		'aerodallas-content',
		__( 'Site Content Guide', 'aero-dallas' ),
		__( 'Guide', 'aero-dallas' ),
		'edit_pages',
		'aerodallas-content',
		'aerodallas_content_overview',
		0
	);
}
add_action( 'admin_menu', 'aerodallas_content_menu' );

/**
 * Render the Site Content guide page.
 */
function aerodallas_content_overview() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	echo '<div class="wrap aerodallas-guide"><h1>' . esc_html__( 'Site Content', 'aero-dallas' ) . '</h1>';
	echo '<p>' . esc_html__( 'Repeatable items shown across the site. Change the order of items with the Order field (lower numbers first). Drafts and trashed items are hidden from visitors.', 'aero-dallas' ) . '</p>';
	echo '<table class="widefat striped"><tbody>';
	foreach ( aerodallas_content_types() as $type => $def ) {
		$count = wp_count_posts( $type );
		printf(
			'<tr><th scope="row"><a href="%1$s">%2$s</a></th><td>%3$s</td><td>%4$s</td></tr>',
			esc_url( admin_url( 'edit.php?post_type=' . $type ) ),
			esc_html( $def['plural'] ),
			/* translators: %d: number of published items. */
			esc_html( sprintf( _n( '%d published', '%d published', (int) $count->publish, 'aero-dallas' ), (int) $count->publish ) ),
			esc_html( $def['help'] )
		);
	}
	echo '</tbody></table>';
	echo '<h2>' . esc_html__( 'Other places to edit', 'aero-dallas' ) . '</h2><ul class="ul-disc">';
	printf( '<li>%s</li>', esc_html__( 'Page text: Pages → edit the page. Headings and short fields are in the "Page sections" box below the editor.', 'aero-dallas' ) );
	printf(
		'<li><a href="%1$s">%2$s</a></li>',
		esc_url( admin_url( 'customize.php?autofocus[panel]=aerodallas_company' ) ),
		esc_html__( 'Phone, email, address, hours, Instagram, quote email and photos: Appearance → Customize → AERO DALLAS INDUSTRIAL — Company Details.', 'aero-dallas' )
	);
	printf(
		'<li><a href="%1$s">%2$s</a></li>',
		esc_url( admin_url( 'edit.php?post_type=quote_request' ) ),
		esc_html__( 'Quote requests submitted through the website.', 'aero-dallas' )
	);
	echo '</ul></div>';
}

/**
 * Show the help text above each content type's list table.
 */
function aerodallas_content_help_notice() {
	$screen = get_current_screen();
	$types  = aerodallas_content_types();
	if ( ! $screen || 'edit' !== $screen->base || ! isset( $types[ $screen->post_type ] ) ) {
		return;
	}
	printf( '<div class="notice notice-info"><p>%s</p></div>', esc_html( $types[ $screen->post_type ]['help'] ) );
}
add_action( 'admin_notices', 'aerodallas_content_help_notice' );

/**
 * Add an Order column to content type list tables and sort by it.
 */
function aerodallas_content_columns_setup() {
	foreach ( array_keys( aerodallas_content_types() ) as $type ) {
		add_filter(
			"manage_{$type}_posts_columns",
			static function ( $columns ) {
				$columns['menu_order'] = __( 'Order', 'aero-dallas' );
				unset( $columns['date'] );
				return $columns;
			}
		);
		add_action(
			"manage_{$type}_posts_custom_column",
			static function ( $column, $post_id ) {
				if ( 'menu_order' === $column ) {
					echo (int) get_post_field( 'menu_order', $post_id );
				}
			},
			10,
			2
		);
	}
}
add_action( 'admin_init', 'aerodallas_content_columns_setup' );

/**
 * Default admin ordering for content types: by Order ascending.
 *
 * @param WP_Query $query Query.
 */
function aerodallas_content_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	$type = $query->get( 'post_type' );
	if ( is_string( $type ) && isset( aerodallas_content_types()[ $type ] ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'aerodallas_content_admin_order' );
