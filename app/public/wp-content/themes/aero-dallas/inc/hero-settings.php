<?php
/**
 * Home hero settings: Appearance → Customize → Home Hero. Heading, text and
 * buttons, text sizes, the slideshow effect and timing, and up to fourteen
 * showcase slides (photo, title, tag, link, fit).
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

define( 'AERODALLAS_HERO_SLOTS', 14 );

/**
 * Default hero settings. The text defaults to what was saved in the Home
 * page's "Page sections" box before these settings existed.
 *
 * @return array<string,mixed>
 */
function aerodallas_hero_defaults() {
	$front = (int) get_option( 'page_on_front' );
	$saved = static function ( $key, $fallback ) use ( $front ) {
		return $front && metadata_exists( 'post', $front, '_aerodallas_' . $key ) ? (string) get_post_meta( $front, '_aerodallas_' . $key, true ) : $fallback;
	};
	return array(
		'hero_eyebrow'    => $saved( 'hero_eyebrow', __( 'Industrial & Aviation Support', 'aero-dallas' ) ),
		'hero_heading'    => $saved( 'hero_heading', __( 'Where Industrial Expertise Meets Dependable Support.', 'aero-dallas' ) ),
		'hero_highlight'  => $saved( 'hero_highlight', __( 'Dependable Support.', 'aero-dallas' ) ),
		'hero_lead'       => $saved( 'hero_lead', __( 'AERO DALLAS INDUSTRIAL provides specialised industrial support solutions across mechanical engineering, aviation ground support, equipment supply, and industrial logistics.', 'aero-dallas' ) ),
		'hero_btn1_label' => __( 'About Us', 'aero-dallas' ),
		'hero_btn1_url'   => '',
		'hero_btn2_label' => __( 'Explore Our Services', 'aero-dallas' ),
		'hero_btn2_url'   => '',
		'hero_title_size' => 60,
		'hero_title_size_mobile' => 32,
		'hero_lead_size'  => 20,
		'hero_effect'     => 'fade',
		'hero_interval'   => 5,
		'hero_speed'      => 0.9,
		'hero_autoplay'   => true,
		'hero_thumbs'     => true,
		'hero_captions'   => true,
		'hero_link_text'  => __( 'Request a quote', 'aero-dallas' ),
	);
}

/**
 * A hero setting.
 *
 * @param string $key Key from aerodallas_hero_defaults().
 * @return mixed
 */
function aerodallas_hero( $key ) {
	$defaults = aerodallas_hero_defaults();
	return get_theme_mod( 'aerodallas_' . $key, $defaults[ $key ] ?? '' );
}

/**
 * Slideshow effects.
 *
 * @return array<string,string>
 */
function aerodallas_hero_effects() {
	return array(
		'fade'  => __( 'Fade', 'aero-dallas' ),
		'slide' => __( 'Slide across', 'aero-dallas' ),
		'zoom'  => __( 'Zoom and fade', 'aero-dallas' ),
		'rise'  => __( 'Rise up', 'aero-dallas' ),
	);
}

/**
 * Default slides: the equipment marked for the hero and the logistics,
 * welding and workshop photos, in the order of their hero number, then
 * empty slots.
 *
 * @return array<int,array{show:bool,photo:string,title:string,tag:string,service:string,fit:string}>
 */
function aerodallas_hero_slot_defaults() {
	$tags  = array(
		'aviation'   => __( 'Aviation', 'aero-dallas' ),
		'industrial' => __( 'Industrial', 'aero-dallas' ),
		'logistics'  => __( 'Logistics', 'aero-dallas' ),
	);
	$extra = static function ( $label, $photo, $service, $hero ) {
		return array(
			'label'   => $label,
			'photo'   => $photo,
			'service' => $service,
			'fit'     => 'cover',
			'hero'    => $hero,
		);
	};
	$items = array_merge(
		array_filter(
			aerodallas_equipment(),
			static function ( $item ) {
				return $item['hero'] > 0;
			}
		),
		array(
			$extra( __( 'Welding & Fabrication', 'aero-dallas' ), 'eq-welding', 'industrial', 4 ),
			$extra( __( 'Metal Fabrication Shop', 'aero-dallas' ), 'eq-fabrication-shop', 'industrial', 5 ),
			$extra( __( 'Box Truck Logistics', 'aero-dallas' ), 'eq-box-truck', 'logistics', 11 ),
			$extra( __( 'Fabrication Workshop', 'aero-dallas' ), 'eq-fabrication-workshop', 'industrial', 12 ),
		)
	);
	usort(
		$items,
		static function ( $a, $b ) {
			return $a['hero'] <=> $b['hero'];
		}
	);
	$slots = array();
	foreach ( $items as $item ) {
		$slots[] = array(
			'show'    => true,
			'photo'   => $item['photo'],
			'title'   => $item['label'],
			'tag'     => $tags[ $item['service'] ] ?? '',
			'service' => $item['service'],
			'fit'     => $item['fit'],
		);
	}
	while ( count( $slots ) < AERODALLAS_HERO_SLOTS ) {
		$slots[] = array(
			'show'    => false,
			'photo'   => '',
			'title'   => '',
			'tag'     => '',
			'service' => '',
			'fit'     => 'cover',
		);
	}
	return array_slice( $slots, 0, AERODALLAS_HERO_SLOTS );
}

/**
 * Home hero showcase slides, as set in the Customizer.
 *
 * @return array<int,array{attachment:int,photo:string,label:string,category:string,fit:string,url:string}>
 */
function aerodallas_hero_showcase() {
	$slides = array();
	foreach ( aerodallas_hero_slot_defaults() as $i => $default ) {
		$n = $i + 1;
		if ( ! get_theme_mod( "aerodallas_hs{$n}_show", $default['show'] ) ) {
			continue;
		}
		$attachment = (int) get_theme_mod( "aerodallas_hs{$n}_image", 0 );
		$attachment = $attachment && wp_attachment_is_image( $attachment ) ? $attachment : 0;
		if ( ! $attachment && '' === $default['photo'] ) {
			continue;
		}
		$title = (string) get_theme_mod( "aerodallas_hs{$n}_title", $default['title'] );
		$url   = (string) get_theme_mod( "aerodallas_hs{$n}_link", '' );
		if ( '' === $url && '' !== $title ) {
			$url = add_query_arg(
				array_filter(
					array(
						'service'   => $default['service'],
						'equipment' => rawurlencode( $title ),
					)
				),
				aerodallas_page_url( 'contact' )
			) . '#quote';
		}
		$slides[] = array(
			'attachment' => $attachment,
			'photo'      => $default['photo'],
			'label'      => $title,
			'category'   => (string) get_theme_mod( "aerodallas_hs{$n}_tag", $default['tag'] ),
			'fit'        => 'contain' === get_theme_mod( "aerodallas_hs{$n}_fit", $default['fit'] ) ? 'contain' : 'cover',
			'url'        => $url,
		);
	}
	return $slides;
}

/**
 * Sanitize a checkbox.
 *
 * @param mixed $value Value.
 * @return bool
 */
function aerodallas_hero_sanitize_bool( $value ) {
	return (bool) $value;
}

/**
 * Sanitize the slideshow effect.
 *
 * @param string $value Value.
 * @return string
 */
function aerodallas_hero_sanitize_effect( $value ) {
	return isset( aerodallas_hero_effects()[ $value ] ) ? $value : 'fade';
}

/**
 * Sanitize a slide's fit.
 *
 * @param string $value Value.
 * @return string
 */
function aerodallas_hero_sanitize_fit( $value ) {
	return 'contain' === $value ? 'contain' : 'cover';
}

/**
 * Number sanitizer limited to a range.
 *
 * @param float $min  Minimum.
 * @param float $max  Maximum.
 * @param bool  $int  Whole numbers only.
 * @return callable
 */
function aerodallas_hero_sanitize_range( $min, $max, $int = true ) {
	return static function ( $value ) use ( $min, $max, $int ) {
		$value = $int ? (int) $value : round( (float) $value, 1 );
		return max( $min, min( $max, $value ) );
	};
}

/**
 * Register the Home Hero panel.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function aerodallas_hero_customize_register( $wp_customize ) {
	$defaults = aerodallas_hero_defaults();
	$panel    = 'aerodallas_home_hero';

	$wp_customize->add_panel(
		$panel,
		array(
			'title'       => __( 'Home Hero', 'aero-dallas' ),
			'description' => __( 'The top section of the home page: heading, text and buttons, text sizes, the equipment slideshow and its slides.', 'aero-dallas' ),
			'priority'    => 24,
		)
	);

	$add = static function ( $section, $key, $control, $sanitize ) use ( $wp_customize, $defaults ) {
		$wp_customize->add_setting(
			'aerodallas_' . $key,
			array(
				'default'           => $control['default'] ?? ( $defaults[ $key ] ?? '' ),
				'sanitize_callback' => $sanitize,
			)
		);
		unset( $control['default'] );
		$wp_customize->add_control( 'aerodallas_' . $key, array_merge( array( 'section' => $section ), $control ) );
	};

	// Heading, text and buttons.
	$wp_customize->add_section(
		'aerodallas_hero_text',
		array(
			'title' => __( 'Heading, text & buttons', 'aero-dallas' ),
			'panel' => $panel,
		)
	);
	$add( 'aerodallas_hero_text', 'hero_eyebrow', array( 'label' => __( 'Small label above the heading', 'aero-dallas' ), 'type' => 'text' ), 'sanitize_text_field' );
	$add( 'aerodallas_hero_text', 'hero_heading', array( 'label' => __( 'Heading', 'aero-dallas' ), 'type' => 'text' ), 'sanitize_text_field' );
	$add(
		'aerodallas_hero_text',
		'hero_highlight',
		array(
			'label'       => __( 'Heading highlight', 'aero-dallas' ),
			'description' => __( 'Words from the heading to show in orange, typed exactly as in the heading.', 'aero-dallas' ),
			'type'        => 'text',
		),
		'sanitize_text_field'
	);
	$add( 'aerodallas_hero_text', 'hero_lead', array( 'label' => __( 'Paragraph under the heading', 'aero-dallas' ), 'type' => 'textarea' ), 'sanitize_textarea_field' );
	$add( 'aerodallas_hero_text', 'hero_btn1_label', array( 'label' => __( 'Orange button: text', 'aero-dallas' ), 'description' => __( 'Leave empty to hide the button.', 'aero-dallas' ), 'type' => 'text' ), 'sanitize_text_field' );
	$add( 'aerodallas_hero_text', 'hero_btn1_url', array( 'label' => __( 'Orange button: link', 'aero-dallas' ), 'description' => __( 'Leave empty to link to the About Us page.', 'aero-dallas' ), 'type' => 'url' ), 'esc_url_raw' );
	$add( 'aerodallas_hero_text', 'hero_btn2_label', array( 'label' => __( 'Second button: text', 'aero-dallas' ), 'description' => __( 'Leave empty to hide the button.', 'aero-dallas' ), 'type' => 'text' ), 'sanitize_text_field' );
	$add( 'aerodallas_hero_text', 'hero_btn2_url', array( 'label' => __( 'Second button: link', 'aero-dallas' ), 'description' => __( 'Leave empty to link to the Services page.', 'aero-dallas' ), 'type' => 'url' ), 'esc_url_raw' );

	// Text sizes.
	$wp_customize->add_section(
		'aerodallas_hero_sizes',
		array(
			'title'       => __( 'Text sizes', 'aero-dallas' ),
			'description' => __( 'Sizes in pixels. The large-screen sizes scale down on tablets; phones use the phone heading size.', 'aero-dallas' ),
			'panel'       => $panel,
		)
	);
	$add( 'aerodallas_hero_sizes', 'hero_title_size', array( 'label' => __( 'Heading size (32–88 px)', 'aero-dallas' ), 'type' => 'number', 'input_attrs' => array( 'min' => 32, 'max' => 88, 'step' => 1 ) ), aerodallas_hero_sanitize_range( 32, 88 ) );
	$add( 'aerodallas_hero_sizes', 'hero_title_size_mobile', array( 'label' => __( 'Heading size on phones (22–48 px)', 'aero-dallas' ), 'type' => 'number', 'input_attrs' => array( 'min' => 22, 'max' => 48, 'step' => 1 ) ), aerodallas_hero_sanitize_range( 22, 48 ) );
	$add( 'aerodallas_hero_sizes', 'hero_lead_size', array( 'label' => __( 'Paragraph size (15–28 px)', 'aero-dallas' ), 'type' => 'number', 'input_attrs' => array( 'min' => 15, 'max' => 28, 'step' => 1 ) ), aerodallas_hero_sanitize_range( 15, 28 ) );

	// Slideshow effect and timing.
	$wp_customize->add_section(
		'aerodallas_hero_motion',
		array(
			'title' => __( 'Slideshow: effect & timing', 'aero-dallas' ),
			'panel' => $panel,
		)
	);
	$add( 'aerodallas_hero_motion', 'hero_effect', array( 'label' => __( 'Transition effect', 'aero-dallas' ), 'type' => 'select', 'choices' => aerodallas_hero_effects() ), 'aerodallas_hero_sanitize_effect' );
	$add( 'aerodallas_hero_motion', 'hero_interval', array( 'label' => __( 'Time on each slide (2–20 seconds)', 'aero-dallas' ), 'type' => 'number', 'input_attrs' => array( 'min' => 2, 'max' => 20, 'step' => 1 ) ), aerodallas_hero_sanitize_range( 2, 20 ) );
	$add( 'aerodallas_hero_motion', 'hero_speed', array( 'label' => __( 'Transition speed (0.2–3 seconds)', 'aero-dallas' ), 'type' => 'number', 'input_attrs' => array( 'min' => 0.2, 'max' => 3, 'step' => 0.1 ) ), aerodallas_hero_sanitize_range( 0.2, 3, false ) );
	$add( 'aerodallas_hero_motion', 'hero_autoplay', array( 'label' => __( 'Change slides automatically', 'aero-dallas' ), 'type' => 'checkbox' ), 'aerodallas_hero_sanitize_bool' );
	$add( 'aerodallas_hero_motion', 'hero_thumbs', array( 'label' => __( 'Show the thumbnails under the photo', 'aero-dallas' ), 'type' => 'checkbox' ), 'aerodallas_hero_sanitize_bool' );
	$add( 'aerodallas_hero_motion', 'hero_captions', array( 'label' => __( 'Show the caption bar on each photo', 'aero-dallas' ), 'type' => 'checkbox' ), 'aerodallas_hero_sanitize_bool' );
	$add( 'aerodallas_hero_motion', 'hero_link_text', array( 'label' => __( 'Caption link text', 'aero-dallas' ), 'type' => 'text' ), 'sanitize_text_field' );

	// Slides.
	foreach ( aerodallas_hero_slot_defaults() as $i => $slot ) {
		$n       = $i + 1;
		$section = 'aerodallas_hero_slide_' . $n;
		$wp_customize->add_section(
			$section,
			array(
				/* translators: 1: slide number, 2: slide title or "empty". */
				'title'       => sprintf( __( 'Slide %1$d: %2$s', 'aero-dallas' ), $n, $slot['title'] ? $slot['title'] : __( 'empty', 'aero-dallas' ) ),
				'description' => $slot['photo'] ? __( 'Leave the photo empty to keep the default photo for this slide.', 'aero-dallas' ) : __( 'Choose a photo and tick "Show this slide" to add it to the slideshow.', 'aero-dallas' ),
				'panel'       => $panel,
			)
		);
		$add( $section, "hs{$n}_show", array( 'label' => __( 'Show this slide', 'aero-dallas' ), 'type' => 'checkbox', 'default' => $slot['show'] ), 'aerodallas_hero_sanitize_bool' );
		$wp_customize->add_setting(
			"aerodallas_hs{$n}_image",
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				"aerodallas_hs{$n}_image",
				array(
					'label'     => __( 'Photo', 'aero-dallas' ),
					'section'   => $section,
					'mime_type' => 'image',
				)
			)
		);
		$add( $section, "hs{$n}_title", array( 'label' => __( 'Title', 'aero-dallas' ), 'type' => 'text', 'default' => $slot['title'] ), 'sanitize_text_field' );
		$add( $section, "hs{$n}_tag", array( 'label' => __( 'Tag (small orange label, e.g. Aviation)', 'aero-dallas' ), 'type' => 'text', 'default' => $slot['tag'] ), 'sanitize_text_field' );
		$add( $section, "hs{$n}_link", array( 'label' => __( 'Link', 'aero-dallas' ), 'description' => __( 'Leave empty to open the quote form with this title filled in.', 'aero-dallas' ), 'type' => 'url', 'default' => '' ), 'esc_url_raw' );
		$add(
			$section,
			"hs{$n}_fit",
			array(
				'label'   => __( 'How the photo fits', 'aero-dallas' ),
				'type'    => 'radio',
				'default' => $slot['fit'],
				'choices' => array(
					'cover'   => __( 'Fill the frame (best for photos)', 'aero-dallas' ),
					'contain' => __( 'Show the whole photo on white (best for product cut-outs)', 'aero-dallas' ),
				),
			),
			'aerodallas_hero_sanitize_fit'
		);
	}
}
add_action( 'customize_register', 'aerodallas_hero_customize_register', 20 );

/**
 * Show the home page in the preview while the Home Hero panel is open.
 */
function aerodallas_hero_customize_controls() {
	$script = sprintf(
		'wp.customize.panel( "aerodallas_home_hero", function ( panel ) { panel.expanded.bind( function ( open ) { if ( open ) { wp.customize.previewer.previewUrl.set( %s ); } } ); } );',
		wp_json_encode( home_url( '/' ) )
	);
	wp_add_inline_script( 'customize-controls', $script );
}
add_action( 'customize_controls_enqueue_scripts', 'aerodallas_hero_customize_controls' );
