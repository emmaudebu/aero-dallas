<?php
/**
 * "Page sections" fields per template, and detail fields for the theme's
 * content types. Native meta boxes; no ACF required.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Page section fields per template key, with defaults taken from the brief.
 *
 * @return array<string,array<string,array{label:string,type:string,default:string,help?:string}>>
 */
function aerodallas_page_fields() {
	$eyebrow = array(
		'label'   => __( 'Eyebrow label (small text above the heading)', 'aero-dallas' ),
		'type'    => 'text',
		'default' => '',
	);
	$heading = array(
		'label'   => __( 'Page heading (leave empty to use the page title)', 'aero-dallas' ),
		'type'    => 'text',
		'default' => '',
	);

	return array(
		'front'    => array(
			'hero_eyebrow'     => array_merge( $eyebrow, array( 'default' => __( 'Industrial & Aviation Support', 'aero-dallas' ) ) ),
			'hero_heading'     => array(
				'label'   => __( 'Hero heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Where Industrial Expertise Meets Dependable Support.', 'aero-dallas' ),
			),
			'hero_highlight'   => array(
				'label'   => __( 'Hero heading highlight (words from the heading shown in orange)', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Dependable Support.', 'aero-dallas' ),
			),
			'hero_lead'        => array(
				'label'   => __( 'Hero lead paragraph', 'aero-dallas' ),
				'type'    => 'textarea',
				'default' => __( 'AERO DALLAS INDUSTRIAL provides specialised industrial support solutions across mechanical engineering, aviation ground support, equipment supply, and industrial logistics.', 'aero-dallas' ),
			),
			'hero_text'        => array(
				'label'   => __( 'Hero supporting paragraph', 'aero-dallas' ),
				'type'    => 'textarea',
				'default' => __( 'We deliver dependable technical assistance, machinery repairs, equipment support, and essential aviation ground support solutions that help businesses maintain operational efficiency. Our commitment to reliability, technical excellence, and professional service ensures that your equipment and operations receive the support they need.', 'aero-dallas' ),
			),
			'services_heading' => array(
				'label'   => __( 'Core services heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Our Core Services', 'aero-dallas' ),
			),
			'about_heading'    => array(
				'label'   => __( 'About intro heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Who We Are', 'aero-dallas' ),
			),
			'about_text'       => array(
				'label'   => __( 'About intro text', 'aero-dallas' ),
				'type'    => 'textarea',
				'default' => __( 'AERO DALLAS INDUSTRIAL is an industrial support company providing specialised services across mechanical engineering, aviation ground support, equipment supply, and industrial logistics.', 'aero-dallas' ) . "\n\n" . __( 'We are committed to helping businesses maintain operational efficiency through dependable technical assistance, quality equipment supply, machinery repairs, and effective logistics solutions.', 'aero-dallas' ),
			),
			'process_heading'  => array(
				'label'   => __( 'How We Work heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'How We Work', 'aero-dallas' ),
			),
			'process_text'     => array(
				'label'   => __( 'How We Work intro', 'aero-dallas' ),
				'type'    => 'textarea',
				'default' => __( 'AERO DALLAS INDUSTRIAL follows a structured approach to ensure that every client receives professional attention, clear communication, and appropriate technical support.', 'aero-dallas' ),
			),
			'why_heading'      => array(
				'label'   => __( 'Why choose heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Why Choose AERO DALLAS INDUSTRIAL?', 'aero-dallas' ),
			),
			'hsq_heading'      => array(
				'label'   => __( 'Health, safety & quality heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Quality Service Begins with Safe Operations', 'aero-dallas' ),
			),
			'faq_heading'      => array(
				'label'   => __( 'FAQ heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Frequently Asked Questions', 'aero-dallas' ),
			),
			'testimonials_heading' => array(
				'label'   => __( 'Testimonials heading (section shows once testimonials are added)', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'What Our Clients Say', 'aero-dallas' ),
			),
			'insights_heading' => array(
				'label'   => __( 'Latest insights heading (section shows when enabled in the Customizer)', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Latest Insights', 'aero-dallas' ),
			),
		),
		'about'    => array(
			'eyebrow'       => array_merge( $eyebrow, array( 'default' => __( 'About Us', 'aero-dallas' ) ) ),
			'heading'       => array_merge( $heading, array( 'default' => __( 'Who We Are', 'aero-dallas' ) ) ),
			'highlight'     => array(
				'label'   => __( 'Heading highlight (words from the heading shown in orange)', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'We Are', 'aero-dallas' ),
			),
			'lead'          => array(
				'label'   => __( 'Hero intro paragraph', 'aero-dallas' ),
				'type'    => 'textarea',
				'default' => __( 'We aim to build lasting business relationships through integrity, commitment, technical excellence, and consistent service delivery.', 'aero-dallas' ),
			),
			'vision'        => array(
				'label'   => __( 'Vision', 'aero-dallas' ),
				'type'    => 'textarea',
				'default' => __( 'To become a trusted name in industrial support, recognised for technical excellence, dependable service delivery, and operational reliability.', 'aero-dallas' ),
			),
			'mission'       => array(
				'label'   => __( 'Mission', 'aero-dallas' ),
				'type'    => 'textarea',
				'default' => __( 'To deliver reliable industrial support services through quality equipment supply, mechanical expertise, efficient logistics, and dedicated technical support.', 'aero-dallas' ),
			),
			'values_heading' => array(
				'label'   => __( 'Core values heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Core Values', 'aero-dallas' ),
			),
			'why_heading'   => array(
				'label'   => __( 'Why choose heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Why Choose AERO DALLAS INDUSTRIAL?', 'aero-dallas' ),
			),
			'hsq_heading'   => array(
				'label'   => __( 'Health, safety & quality heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Quality Service Begins with Safe Operations', 'aero-dallas' ),
			),
		),
		'services' => array(
			'eyebrow' => array_merge( $eyebrow, array( 'default' => __( 'Our Services', 'aero-dallas' ) ) ),
			'heading' => array_merge( $heading, array( 'default' => __( 'Comprehensive Industrial & Aviation Support Solutions', 'aero-dallas' ) ) ),
			'highlight' => array(
				'label'   => __( 'Heading highlight (words from the heading shown in orange)', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Support Solutions', 'aero-dallas' ),
			),
		),
		'process'  => array(
			'eyebrow'            => array_merge( $eyebrow, array( 'default' => __( 'Mode of Operation', 'aero-dallas' ) ) ),
			'heading'            => $heading,
			'highlight'          => array(
				'label'   => __( 'Heading highlight (words from the heading shown in orange)', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Work', 'aero-dallas' ),
			),
			'commitment_heading' => array(
				'label'   => __( 'Commitment heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Our Commitment', 'aero-dallas' ),
			),
			'commitment_text'    => array(
				'label'   => __( 'Commitment text', 'aero-dallas' ),
				'type'    => 'textarea',
				'default' => __( 'We prioritise transparency, professionalism, and clear communication throughout every stage of the service process.', 'aero-dallas' ),
			),
		),
		'policies' => array(
			'eyebrow'   => array_merge( $eyebrow, array( 'default' => __( 'Company Policies', 'aero-dallas' ) ) ),
			'heading'   => array_merge( $heading, array( 'default' => __( 'Our Company Policies', 'aero-dallas' ) ) ),
			'highlight' => array(
				'label'   => __( 'Heading highlight (words from the heading shown in orange)', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Policies', 'aero-dallas' ),
			),
			'lead'      => array(
				'label'   => __( 'Hero intro paragraph', 'aero-dallas' ),
				'type'    => 'textarea',
				'default' => __( 'How we handle quotations, payments, service delivery, cancellations, warranties, health and safety, and your personal information.', 'aero-dallas' ),
			),
		),
		'contact'  => array(
			'eyebrow'      => array_merge( $eyebrow, array( 'default' => __( 'Contact Us', 'aero-dallas' ) ) ),
			'heading'      => array_merge( $heading, array( 'default' => __( "Let's Support Your Operations", 'aero-dallas' ) ) ),
			'highlight'    => array(
				'label'   => __( 'Heading highlight (words from the heading shown in orange)', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Your Operations', 'aero-dallas' ),
			),
			'form_heading' => array(
				'label'   => __( 'Form heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Request a Quote', 'aero-dallas' ),
			),
			'faq_heading'  => array(
				'label'   => __( 'FAQ heading', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Frequently Asked Questions', 'aero-dallas' ),
			),
		),
		'news'     => array(
			'eyebrow'   => array_merge( $eyebrow, array( 'default' => __( 'News & Insights', 'aero-dallas' ) ) ),
			'heading'   => array_merge( $heading, array( 'default' => __( 'Logistics News & Industry Insights', 'aero-dallas' ) ) ),
			'highlight' => array(
				'label'   => __( 'Heading highlight (words from the heading shown in orange)', 'aero-dallas' ),
				'type'    => 'text',
				'default' => __( 'Logistics News', 'aero-dallas' ),
			),
			'lead'      => array(
				'label'   => __( 'Hero intro paragraph', 'aero-dallas' ),
				'type'    => 'textarea',
				'default' => __( 'Updates, insights and company news from AERO DALLAS INDUSTRIAL on air and ground logistics, aviation ground support, parts supply and industrial support.', 'aero-dallas' ),
			),
		),
		'default'  => array(
			'eyebrow' => $eyebrow,
			'heading' => $heading,
		),
	);
}

/**
 * Which field set a page uses.
 *
 * @param int $post_id Page ID.
 * @return string Template key.
 */
function aerodallas_template_key( $post_id ) {
	if ( (int) get_option( 'page_on_front' ) === (int) $post_id ) {
		return 'front';
	}
	if ( (int) get_option( 'page_for_posts' ) === (int) $post_id ) {
		return 'news';
	}
	$map = array(
		'page-templates/about.php'       => 'about',
		'page-templates/services.php'    => 'services',
		'page-templates/how-we-work.php' => 'process',
		'page-templates/policies.php'    => 'policies',
		'page-templates/contact.php'     => 'contact',
	);
	return $map[ get_page_template_slug( $post_id ) ] ?? 'default';
}

/**
 * Default values for every field the given page could show.
 *
 * @param int|null $post_id Page ID.
 * @return array<string,string>
 */
function aerodallas_field_defaults( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$fields  = aerodallas_page_fields();
	$set     = $fields[ aerodallas_template_key( (int) $post_id ) ] ?? array();
	return wp_list_pluck( $set, 'default' );
}

/**
 * Register meta boxes.
 */
function aerodallas_add_meta_boxes() {
	add_meta_box( 'aerodallas-page-sections', __( 'Page sections', 'aero-dallas' ), 'aerodallas_page_sections_box', 'page', 'normal', 'high' );
	add_meta_box( 'aerodallas-seo', __( 'Search & sharing', 'aero-dallas' ), 'aerodallas_seo_box', array( 'page', 'post', 'aerodallas_service' ), 'normal', 'default' );
	add_meta_box( 'aerodallas-service', __( 'Service details', 'aero-dallas' ), 'aerodallas_service_box', 'aerodallas_service', 'normal', 'high' );
	add_meta_box( 'aerodallas-icon', __( 'Icon', 'aero-dallas' ), 'aerodallas_icon_box', array( 'aerodallas_value', 'aerodallas_step', 'aerodallas_reason' ), 'side', 'default' );
	add_meta_box( 'aerodallas-testimonial', __( 'Client details', 'aero-dallas' ), 'aerodallas_testimonial_box', 'aerodallas_testimonial', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'aerodallas_add_meta_boxes' );

/**
 * Current saved value or the default when never saved.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Field key.
 * @param string $default Default.
 * @return string
 */
function aerodallas_meta_or_default( $post_id, $key, $default ) {
	return metadata_exists( 'post', $post_id, '_aerodallas_' . $key ) ? (string) get_post_meta( $post_id, '_aerodallas_' . $key, true ) : $default;
}

/**
 * Render one field row.
 *
 * @param string $key   Field key.
 * @param array  $field Field definition.
 * @param string $value Current value.
 */
function aerodallas_render_field( $key, $field, $value ) {
	$id = 'aerodallas-' . $key;
	echo '<p class="aerodallas-field">';
	printf( '<label for="%1$s"><strong>%2$s</strong></label><br>', esc_attr( $id ), esc_html( $field['label'] ) );
	if ( 'textarea' === $field['type'] ) {
		printf( '<textarea id="%1$s" name="aerodallas[%2$s]" rows="4" class="widefat">%3$s</textarea>', esc_attr( $id ), esc_attr( $key ), esc_textarea( $value ) );
	} elseif ( 'select' === $field['type'] ) {
		printf( '<select id="%1$s" name="aerodallas[%2$s]">', esc_attr( $id ), esc_attr( $key ) );
		foreach ( $field['options'] as $opt_value => $opt_label ) {
			printf( '<option value="%1$s"%3$s>%2$s</option>', esc_attr( $opt_value ), esc_html( $opt_label ), selected( $value, $opt_value, false ) );
		}
		echo '</select>';
	} else {
		printf( '<input type="text" id="%1$s" name="aerodallas[%2$s]" value="%3$s" class="widefat">', esc_attr( $id ), esc_attr( $key ), esc_attr( $value ) );
	}
	if ( ! empty( $field['help'] ) ) {
		printf( '<span class="description">%s</span>', esc_html( $field['help'] ) );
	}
	echo '</p>';
}

/**
 * Page sections meta box.
 *
 * @param WP_Post $post Page.
 */
function aerodallas_page_sections_box( $post ) {
	wp_nonce_field( 'aerodallas_save_meta', 'aerodallas_meta_nonce' );
	$key    = aerodallas_template_key( $post->ID );
	$fields = aerodallas_page_fields()[ $key ];

	$notes = array(
		'front'    => __( 'The home page is built from these fields plus Site Content (services, steps, why-choose points, FAQs). The main editor content of this page is not shown.', 'aero-dallas' ),
		'services' => __( 'The main editor content is the intro shown in the hero. The four service sections come from Site Content → Services.', 'aero-dallas' ),
		'process'  => __( 'The main editor content is the intro shown in the hero. The steps come from Site Content → Process Steps.', 'aero-dallas' ),
		'policies' => __( 'The main editor content holds the policies. Each Heading block becomes an entry in the table of contents; set its HTML anchor (Advanced panel) to control the link, e.g. "privacy".', 'aero-dallas' ),
		'contact'  => __( 'The main editor content is the intro shown in the hero. Contact details come from Appearance → Customize → AERO DALLAS INDUSTRIAL — Company Details.', 'aero-dallas' ),
		'about'    => __( 'The main editor content is the "Who We Are" text. The hero photo can be replaced with a featured image.', 'aero-dallas' ),
		'default'  => __( 'Choose a page template in the Page panel to see more fields after saving.', 'aero-dallas' ),
	);
	printf( '<p class="description">%s</p>', esc_html( $notes[ $key ] ) );

	foreach ( $fields as $field_key => $field ) {
		aerodallas_render_field( $field_key, $field, aerodallas_meta_or_default( $post->ID, $field_key, $field['default'] ) );
	}
	echo '<input type="hidden" name="aerodallas_fieldset" value="' . esc_attr( $key ) . '">';
}

/**
 * Search & sharing meta box.
 *
 * @param WP_Post $post Post.
 */
function aerodallas_seo_box( $post ) {
	wp_nonce_field( 'aerodallas_save_seo', 'aerodallas_seo_nonce' );
	aerodallas_render_field(
		'seo_description',
		array(
			'label' => __( 'Meta description', 'aero-dallas' ),
			'type'  => 'textarea',
			'help'  => __( 'Shown in search results and link previews. About 150 characters. Ignored when an SEO plugin is active.', 'aero-dallas' ),
		),
		(string) get_post_meta( $post->ID, '_aerodallas_seo_description', true )
	);
}

/**
 * Service details meta box.
 *
 * @param WP_Post $post Service.
 */
function aerodallas_service_box( $post ) {
	wp_nonce_field( 'aerodallas_save_meta', 'aerodallas_meta_nonce' );
	$photos = array( '' => __( '— None —', 'aero-dallas' ) );
	foreach ( aerodallas_photos() as $key => $alt ) {
		$photos[ $key ] = $key;
	}
	$fields = array(
		'intro'      => array(
			'label' => __( 'Hero intro (shown under the title on the service page and on the Services page)', 'aero-dallas' ),
			'type'  => 'textarea',
		),
		'scope'      => array(
			'label' => __( '"What\'s included" list — one item per line', 'aero-dallas' ),
			'type'  => 'textarea',
		),
		'card_title' => array(
			'label' => __( 'Home page card title', 'aero-dallas' ),
			'type'  => 'text',
			'help'  => __( 'Shorter name used on the home page, e.g. "Aviation Ground Support".', 'aero-dallas' ),
		),
		'anchor'     => array(
			'label' => __( 'Section anchor', 'aero-dallas' ),
			'type'  => 'text',
			'help'  => __( 'Lowercase, no spaces. Used in links such as /services/#aviation and to pre-select this service in the quote form.', 'aero-dallas' ),
		),
		'icon'       => array(
			'label'   => __( 'Icon', 'aero-dallas' ),
			'type'    => 'select',
			'options' => aerodallas_icon_choices(),
		),
		'photo'      => array(
			'label'   => __( 'Card photo (home page and Services page)', 'aero-dallas' ),
			'type'    => 'select',
			'options' => $photos,
		),
		'hero_photo' => array(
			'label'   => __( 'Service page hero photo (a featured image replaces it)', 'aero-dallas' ),
			'type'    => 'select',
			'options' => $photos,
		),
		'gallery_photo' => array(
			'label'   => __( 'Service page secondary photo', 'aero-dallas' ),
			'type'    => 'select',
			'options' => $photos,
		),
	);
	foreach ( $fields as $key => $field ) {
		aerodallas_render_field( $key, $field, (string) get_post_meta( $post->ID, '_aerodallas_' . $key, true ) );
	}
	echo '<input type="hidden" name="aerodallas_fieldset" value="service">';
}

/**
 * Icon meta box for values and steps.
 *
 * @param WP_Post $post Item.
 */
function aerodallas_icon_box( $post ) {
	wp_nonce_field( 'aerodallas_save_meta', 'aerodallas_meta_nonce' );
	aerodallas_render_field(
		'icon',
		array(
			'label'   => __( 'Icon', 'aero-dallas' ),
			'type'    => 'select',
			'options' => aerodallas_icon_choices(),
		),
		(string) get_post_meta( $post->ID, '_aerodallas_icon', true )
	);
	echo '<input type="hidden" name="aerodallas_fieldset" value="icon">';
}

/**
 * Testimonial client details meta box.
 *
 * @param WP_Post $post Testimonial.
 */
function aerodallas_testimonial_box( $post ) {
	wp_nonce_field( 'aerodallas_save_meta', 'aerodallas_meta_nonce' );
	aerodallas_render_field(
		'role',
		array(
			'label' => __( 'Role and company', 'aero-dallas' ),
			'type'  => 'text',
			'help'  => __( 'For example "Operations Manager, Company name". Shown under the name.', 'aero-dallas' ),
		),
		(string) get_post_meta( $post->ID, '_aerodallas_role', true )
	);
	echo '<input type="hidden" name="aerodallas_fieldset" value="testimonial">';
}

/**
 * Save meta box fields.
 *
 * @param int $post_id Post ID.
 */
function aerodallas_save_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['aerodallas_seo_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['aerodallas_seo_nonce'] ), 'aerodallas_save_seo' ) ) {
		$desc = isset( $_POST['aerodallas']['seo_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['aerodallas']['seo_description'] ) ) : '';
		update_post_meta( $post_id, '_aerodallas_seo_description', $desc );
	}

	if ( ! isset( $_POST['aerodallas_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['aerodallas_meta_nonce'] ), 'aerodallas_save_meta' ) ) {
		return;
	}

	$set    = isset( $_POST['aerodallas_fieldset'] ) ? sanitize_key( $_POST['aerodallas_fieldset'] ) : '';
	$input  = isset( $_POST['aerodallas'] ) && is_array( $_POST['aerodallas'] ) ? wp_unslash( $_POST['aerodallas'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised per field below.
	$fields = array();

	if ( 'service' === $set ) {
		$fields = array(
			'intro'         => 'textarea',
			'scope'         => 'textarea',
			'card_title'    => 'text',
			'anchor'        => 'slug',
			'icon'          => 'icon',
			'photo'         => 'photo',
			'hero_photo'    => 'photo',
			'gallery_photo' => 'photo',
		);
	} elseif ( 'icon' === $set ) {
		$fields = array( 'icon' => 'icon' );
	} elseif ( 'testimonial' === $set ) {
		$fields = array( 'role' => 'text' );
	} else {
		$page_fields = aerodallas_page_fields();
		if ( isset( $page_fields[ $set ] ) ) {
			$fields = wp_list_pluck( $page_fields[ $set ], 'type' );
		}
	}

	foreach ( $fields as $key => $type ) {
		if ( ! array_key_exists( $key, $input ) ) {
			continue;
		}
		$raw = (string) $input[ $key ];
		switch ( $type ) {
			case 'textarea':
				$value = sanitize_textarea_field( $raw );
				break;
			case 'slug':
				$value = sanitize_title( $raw );
				break;
			case 'icon':
				$value = array_key_exists( $raw, aerodallas_icon_choices() ) ? $raw : '';
				break;
			case 'photo':
				$value = array_key_exists( $raw, aerodallas_photos() ) ? $raw : '';
				break;
			default:
				$value = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, '_aerodallas_' . $key, $value );
	}
}
add_action( 'save_post', 'aerodallas_save_meta' );
