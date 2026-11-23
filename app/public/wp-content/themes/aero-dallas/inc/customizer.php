<?php
/**
 * Customizer: company details, quote notification email, call-to-action band
 * and photo overrides.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default text of the call-to-action band.
 *
 * @return array<string,string>
 */
function aerodallas_cta_defaults() {
	return array(
		'cta_heading' => __( 'Ready to Discuss Your Requirements?', 'aero-dallas' ),
		'cta_text'    => __( 'Share your equipment requirements and specifications. Our team will review your requirements and guide you through the next steps.', 'aero-dallas' ),
	);
}

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function aerodallas_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'aerodallas_company',
		array(
			'title'       => __( 'AERO DALLAS INDUSTRIAL — Company Details', 'aero-dallas' ),
			'description' => __( 'Any field left empty is simply not shown on the website.', 'aero-dallas' ),
			'priority'    => 25,
		)
	);

	$wp_customize->add_section(
		'aerodallas_contact',
		array(
			'title' => __( 'Contact details', 'aero-dallas' ),
			'panel' => 'aerodallas_company',
		)
	);

	$defaults = aerodallas_detail_defaults();
	$fields   = array(
		'phone'         => array( __( 'Phone number', 'aero-dallas' ), 'text', 'sanitize_text_field', __( 'Shown as written; the call link is built from the digits.', 'aero-dallas' ) ),
		'email'         => array( __( 'Business email', 'aero-dallas' ), 'email', 'sanitize_email', '' ),
		'address'       => array( __( 'Business address', 'aero-dallas' ), 'textarea', 'sanitize_textarea_field', __( 'One line per address line.', 'aero-dallas' ) ),
		'hours'         => array( __( 'Business hours', 'aero-dallas' ), 'textarea', 'sanitize_textarea_field', __( 'One line per day or range, e.g. "Mon–Fri: 8:00–17:00".', 'aero-dallas' ) ),
		'regions'       => array( __( 'Regions served', 'aero-dallas' ), 'text', 'sanitize_text_field', '' ),
		'instagram_url' => array( __( 'Instagram profile URL', 'aero-dallas' ), 'url', 'esc_url_raw', '' ),
		'quote_email'   => array( __( 'Quote notification email', 'aero-dallas' ), 'email', 'sanitize_email', __( 'Where new quote requests are sent. Leave empty to use the site admin email.', 'aero-dallas' ) ),
	);

	foreach ( $fields as $key => $field ) {
		list( $label, $type, $sanitize, $description ) = $field;
		$wp_customize->add_setting(
			'aerodallas_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'aerodallas_' . $key,
			array(
				'label'       => $label,
				'description' => $description,
				'section'     => 'aerodallas_contact',
				'type'        => $type,
			)
		);
	}

	// Call-to-action band.
	$wp_customize->add_section(
		'aerodallas_cta',
		array(
			'title' => __( 'Quote call-to-action band', 'aero-dallas' ),
			'panel' => 'aerodallas_company',
		)
	);
	foreach ( aerodallas_cta_defaults() as $key => $default ) {
		$wp_customize->add_setting(
			'aerodallas_' . $key,
			array(
				'default'           => $default,
				'sanitize_callback' => 'sanitize_textarea_field',
			)
		);
		$wp_customize->add_control(
			'aerodallas_' . $key,
			array(
				'label'   => 'cta_heading' === $key ? __( 'Heading', 'aero-dallas' ) : __( 'Text', 'aero-dallas' ),
				'section' => 'aerodallas_cta',
				'type'    => 'cta_heading' === $key ? 'text' : 'textarea',
			)
		);
	}

	// Appearance: light / dark mode.
	$wp_customize->add_section(
		'aerodallas_appearance',
		array(
			'title'       => __( 'Appearance (light / dark)', 'aero-dallas' ),
			'panel'       => 'aerodallas_company',
			'description' => __( 'Light mode uses the bright hero with the quick-quote bar; dark mode uses the dark hero with the feature strip. Visitors can switch with the toggle in the header, and their choice is remembered on their device.', 'aero-dallas' ),
		)
	);
	$wp_customize->add_setting(
		'aerodallas_default_mode',
		array(
			'default'           => 'light',
			'sanitize_callback' => static function ( $value ) {
				return in_array( $value, array( 'light', 'dark', 'system' ), true ) ? $value : 'light';
			},
		)
	);
	$wp_customize->add_control(
		'aerodallas_default_mode',
		array(
			'label'   => __( 'Default mode', 'aero-dallas' ),
			'section' => 'aerodallas_appearance',
			'type'    => 'radio',
			'choices' => array(
				'light'  => __( 'Light', 'aero-dallas' ),
				'dark'   => __( 'Dark', 'aero-dallas' ),
				'system' => __( "Follow the visitor's device setting", 'aero-dallas' ),
			),
		)
	);
	$wp_customize->add_setting(
		'aerodallas_show_toggle',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);
	$wp_customize->add_control(
		'aerodallas_show_toggle',
		array(
			'label'   => __( 'Show the light/dark toggle in the header', 'aero-dallas' ),
			'section' => 'aerodallas_appearance',
			'type'    => 'checkbox',
		)
	);

	// Home page extras that stay hidden until filled in.
	$wp_customize->add_section(
		'aerodallas_home_extras',
		array(
			'title'       => __( 'Home: statistics and insights', 'aero-dallas' ),
			'panel'       => 'aerodallas_company',
			'description' => __( 'The statistics band only appears when at least one number and label are filled in. Use confirmed figures only.', 'aero-dallas' ),
		)
	);
	$wp_customize->add_setting( 'aerodallas_stats_heading', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'aerodallas_stats_heading', array( 'label' => __( 'Statistics heading (optional)', 'aero-dallas' ), 'section' => 'aerodallas_home_extras', 'type' => 'text' ) );
	for ( $i = 1; $i <= 4; $i++ ) {
		foreach ( array( 'value', 'label' ) as $part ) {
			$id = 'aerodallas_stat_' . $i . '_' . $part;
			$wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
			$wp_customize->add_control(
				$id,
				array(
					/* translators: %d: statistic number. */
					'label'   => sprintf( 'value' === $part ? __( 'Statistic %d — number (e.g. 120+)', 'aero-dallas' ) : __( 'Statistic %d — label', 'aero-dallas' ), $i ),
					'section' => 'aerodallas_home_extras',
					'type'    => 'text',
				)
			);
		}
	}
	$wp_customize->add_setting( 'aerodallas_show_insights', array( 'default' => false, 'sanitize_callback' => 'wp_validate_boolean' ) );
	$wp_customize->add_control(
		'aerodallas_show_insights',
		array(
			'label'       => __( 'Show "Latest Insights" (3 newest blog posts) on the home page', 'aero-dallas' ),
			'description' => __( 'Turn on once you have published articles under Posts.', 'aero-dallas' ),
			'section'     => 'aerodallas_home_extras',
			'type'        => 'checkbox',
		)
	);

	// Photos.
	$wp_customize->add_section(
		'aerodallas_photos',
		array(
			'title'       => __( 'Photos', 'aero-dallas' ),
			'panel'       => 'aerodallas_company',
			'description' => __( 'Replace the default stock photos with your own. Service photos are set as each service\'s featured image under Site Content → Services.', 'aero-dallas' ),
		)
	);
	$photos = array(
		'hero_image'  => __( 'Home hero photo', 'aero-dallas' ),
		'about_image' => __( 'About / "Who We Are" photo', 'aero-dallas' ),
		'detail_image' => __( 'Vision & mission photo (About page)', 'aero-dallas' ),
		'process_image' => __( '"How We Work" band background', 'aero-dallas' ),
		'cta_image'     => __( 'Quote band background', 'aero-dallas' ),
	);
	foreach ( $photos as $key => $label ) {
		$wp_customize->add_setting(
			'aerodallas_' . $key,
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'aerodallas_' . $key,
				array(
					'label'     => $label,
					'section'   => 'aerodallas_photos',
					'mime_type' => 'image',
				)
			)
		);
	}
}
add_action( 'customize_register', 'aerodallas_customize_register' );

/**
 * CTA band text.
 *
 * @param string $key cta_heading|cta_text.
 * @return string
 */
function aerodallas_cta( $key ) {
	$defaults = aerodallas_cta_defaults();
	return trim( (string) get_theme_mod( 'aerodallas_' . $key, $defaults[ $key ] ?? '' ) );
}
