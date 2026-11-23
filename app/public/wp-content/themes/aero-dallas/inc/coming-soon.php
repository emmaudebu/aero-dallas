<?php
/**
 * Coming soon mode: while switched on in the Customizer, visitors see an
 * animated holding page with a countdown to the launch date. Editors and
 * administrators who are logged in keep seeing the full website.
 *
 * Visitors get a 503 "temporarily unavailable" status with Retry-After, so
 * search engines keep any existing rankings and come back later.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

// Query argument that shows the page to logged-in editors (also used by the Customizer).
define( 'AERODALLAS_CS_PREVIEW', 'coming-soon-preview' );

/**
 * Default text of the coming soon page.
 *
 * @return array<string,string>
 */
function aerodallas_cs_defaults() {
	return array(
		'cs_eyebrow'   => __( 'Coming Soon', 'aero-dallas' ),
		'cs_heading'   => __( 'Our New Website Is Launching Soon', 'aero-dallas' ),
		'cs_highlight' => __( 'Launching Soon', 'aero-dallas' ),
		'cs_text'      => __( 'We are preparing a new online home for AERO DALLAS INDUSTRIAL: mechanical engineering support, aviation ground support, parts and equipment supply, and air and ground logistics. In the meantime, our team is available to discuss your requirements.', 'aero-dallas' ),
	);
}

/**
 * Coming soon page text.
 *
 * @param string $key cs_eyebrow|cs_heading|cs_highlight|cs_text.
 * @return string
 */
function aerodallas_cs( $key ) {
	$defaults = aerodallas_cs_defaults();
	return trim( (string) get_theme_mod( 'aerodallas_' . $key, $defaults[ $key ] ?? '' ) );
}

/**
 * Keep a launch date in the datetime-local format (Y-m-d\TH:i), or empty.
 *
 * @param string $value Raw value.
 * @return string
 */
function aerodallas_cs_sanitize_launch( $value ) {
	$value = trim( (string) $value );
	if ( ! preg_match( '/^(\d{4}-\d{2}-\d{2})[T ](\d{2}:\d{2})/', $value, $m ) ) {
		return '';
	}
	$date = DateTimeImmutable::createFromFormat( '!Y-m-d H:i', $m[1] . ' ' . $m[2], wp_timezone() );
	// Reject impossible dates such as 30 February instead of rolling them over.
	return ( $date && $date->format( 'Y-m-d H:i' ) === $m[1] . ' ' . $m[2] ) ? $date->format( 'Y-m-d\TH:i' ) : '';
}

/**
 * Launch date and time in the site time zone, if set.
 *
 * @return DateTimeImmutable|null
 */
function aerodallas_cs_launch() {
	$value = aerodallas_cs_sanitize_launch( get_theme_mod( 'aerodallas_cs_launch', '' ) );
	if ( '' === $value ) {
		return null;
	}
	$date = DateTimeImmutable::createFromFormat( '!Y-m-d\TH:i', $value, wp_timezone() );
	return $date ? $date : null;
}

/**
 * Short name of the site time zone, e.g. "CST" or "UTC+01:00".
 *
 * @param int|null $timestamp Moment to name the zone at (daylight saving), or now.
 * @return string
 */
function aerodallas_cs_zone_label( $timestamp = null ) {
	$zone = wp_timezone_string();
	if ( preg_match( '/^[+-]/', $zone ) ) {
		return '+00:00' === $zone ? 'UTC' : 'UTC' . $zone;
	}
	$abbr = wp_date( 'T', $timestamp );
	return preg_match( '/^[+-]/', $abbr ) ? 'UTC' . $abbr : $abbr;
}

/**
 * Whether visitors should currently see the coming soon page. It switches
 * itself off once the launch time has passed, unless that is turned off.
 *
 * @return bool
 */
function aerodallas_cs_active() {
	if ( ! get_theme_mod( 'aerodallas_cs_enabled', false ) ) {
		return false;
	}
	$launch = aerodallas_cs_launch();
	if ( $launch && $launch->getTimestamp() <= time() && get_theme_mod( 'aerodallas_cs_auto_open', true ) ) {
		return false;
	}
	return true;
}

/**
 * Whether this request is an editor's preview of the page.
 *
 * @return bool
 */
function aerodallas_cs_is_preview() {
	return isset( $_GET[ AERODALLAS_CS_PREVIEW ] ) && current_user_can( 'edit_pages' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display switch.
}

/**
 * Serve the coming soon page instead of the requested page: set the status
 * and headers here, and swap the template in template_include.
 */
function aerodallas_cs_maybe_show() {
	$preview = aerodallas_cs_is_preview();
	if ( ! $preview ) {
		if ( ! aerodallas_cs_active() || is_robots() || is_favicon() ) {
			return;
		}
		if ( current_user_can( 'edit_pages' ) ) {
			return;
		}
	}

	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true ); // Page caches must not keep the holding page.
	}
	nocache_headers();

	if ( $preview ) {
		add_filter( 'wp_robots', 'wp_robots_no_robots' );
	} else {
		$launch = aerodallas_cs_launch();
		$wait   = $launch ? $launch->getTimestamp() - time() : 0;
		status_header( 503 );
		header( 'Retry-After: ' . ( $wait > 0 ? min( $wait, DAY_IN_SECONDS ) : HOUR_IN_SECONDS ) );
	}

	add_filter(
		'pre_get_document_title',
		static function () {
			return __( 'Coming Soon', 'aero-dallas' ) . ' ' . apply_filters( 'document_title_separator', '-' ) . ' ' . get_bloginfo( 'name' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core filter.
		}
	);
	add_filter(
		'body_class',
		static function ( $classes ) {
			$classes[] = 'coming-soon';
			return $classes;
		}
	);
	add_action( 'wp_enqueue_scripts', 'aerodallas_cs_assets', 20 );

	// Feeds and trackbacks never reach the template loader below.
	if ( is_feed() || is_trackback() ) {
		header( 'Content-Type: text/plain; charset=' . get_option( 'blog_charset' ) );
		echo esc_html__( 'This website is coming soon.', 'aero-dallas' );
		exit;
	}

	// Through the normal template loader, so WordPress prints its styles in order.
	add_filter(
		'template_include',
		static function () {
			return AERODALLAS_DIR . '/template-parts/coming-soon.php';
		},
		PHP_INT_MAX
	);
}
add_action( 'template_redirect', 'aerodallas_cs_maybe_show', 1 );

/**
 * Styles and countdown script for the coming soon page.
 */
function aerodallas_cs_assets() {
	wp_enqueue_style( 'aerodallas-coming-soon', AERODALLAS_URI . '/assets/css/coming-soon.css', array( 'aerodallas-main' ), aerodallas_asset_version( 'assets/css/coming-soon.css' ) );
	wp_enqueue_script(
		'aerodallas-coming-soon',
		AERODALLAS_URI . '/assets/js/coming-soon.js',
		array(),
		aerodallas_asset_version( 'assets/js/coming-soon.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}

/**
 * Customizer section.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function aerodallas_cs_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'aerodallas_coming_soon',
		array(
			'title'       => __( 'Coming soon mode', 'aero-dallas' ),
			'panel'       => 'aerodallas_company',
			'priority'    => 5,
			'description' => __( 'While this section is open, the preview shows the coming soon page. Logged-in editors and administrators always see the full website; visitors, search engines and feeds see the coming soon page with a "temporarily unavailable" status, so search rankings are kept.', 'aero-dallas' ),
		)
	);

	$wp_customize->add_setting( 'aerodallas_cs_enabled', array( 'default' => false, 'sanitize_callback' => 'wp_validate_boolean' ) );
	$wp_customize->add_control(
		'aerodallas_cs_enabled',
		array(
			'label'   => __( 'Show the coming soon page to visitors', 'aero-dallas' ),
			'section' => 'aerodallas_coming_soon',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting( 'aerodallas_cs_launch', array( 'default' => '', 'sanitize_callback' => 'aerodallas_cs_sanitize_launch' ) );
	$wp_customize->add_control(
		'aerodallas_cs_launch',
		array(
			'label'       => __( 'Launch date and time', 'aero-dallas' ),
			/* translators: %s: time zone name. */
			'description' => sprintf( __( 'Shown as a live countdown. Uses the site time zone (%s), set under Settings → General. Leave empty to hide the countdown.', 'aero-dallas' ), aerodallas_cs_zone_label() ),
			'section'     => 'aerodallas_coming_soon',
			'type'        => 'datetime-local',
		)
	);

	$wp_customize->add_setting( 'aerodallas_cs_auto_open', array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
	$wp_customize->add_control(
		'aerodallas_cs_auto_open',
		array(
			'label'       => __( 'Open the website automatically when the countdown ends', 'aero-dallas' ),
			'description' => __( 'When off, the page stays up after the launch time and says the launch is moments away, until you switch coming soon mode off.', 'aero-dallas' ),
			'section'     => 'aerodallas_coming_soon',
			'type'        => 'checkbox',
		)
	);

	$labels = array(
		'cs_eyebrow'   => array( __( 'Small label above the heading', 'aero-dallas' ), 'text' ),
		'cs_heading'   => array( __( 'Heading', 'aero-dallas' ), 'text' ),
		'cs_highlight' => array( __( 'Highlighted words (must appear in the heading)', 'aero-dallas' ), 'text' ),
		'cs_text'      => array( __( 'Text', 'aero-dallas' ), 'textarea' ),
	);
	foreach ( aerodallas_cs_defaults() as $key => $default ) {
		$wp_customize->add_setting(
			'aerodallas_' . $key,
			array(
				'default'           => $default,
				'sanitize_callback' => 'textarea' === $labels[ $key ][1] ? 'sanitize_textarea_field' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'aerodallas_' . $key,
			array(
				'label'   => $labels[ $key ][0],
				'section' => 'aerodallas_coming_soon',
				'type'    => $labels[ $key ][1],
			)
		);
	}

	$wp_customize->add_setting( 'aerodallas_cs_image', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'aerodallas_cs_image',
			array(
				'label'       => __( 'Photo', 'aero-dallas' ),
				'description' => __( 'Leave empty to use the jet at sunset.', 'aero-dallas' ),
				'section'     => 'aerodallas_coming_soon',
				'mime_type'   => 'image',
			)
		)
	);
}
add_action( 'customize_register', 'aerodallas_cs_customize_register', 20 );

/**
 * Point the Customizer preview at the coming soon page while its section is open.
 */
function aerodallas_cs_customize_controls() {
	$script = sprintf(
		'wp.customize.section(%1$s,function(s){s.expanded.bind(function(open){var p=wp.customize.previewer.previewUrl,u=new URL(p.get());if(open){u.searchParams.set(%2$s,"1");}else{u.searchParams.delete(%2$s);}p.set(u.toString());});});',
		wp_json_encode( 'aerodallas_coming_soon' ),
		wp_json_encode( AERODALLAS_CS_PREVIEW )
	);
	wp_add_inline_script( 'customize-controls', $script );
}
add_action( 'customize_controls_enqueue_scripts', 'aerodallas_cs_customize_controls' );

/**
 * Admin bar reminder while coming soon mode is on, linking to its settings.
 *
 * @param WP_Admin_Bar $bar Admin bar.
 */
function aerodallas_cs_admin_bar( $bar ) {
	if ( ! aerodallas_cs_active() || ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	$bar->add_node(
		array(
			'id'    => 'aerodallas-coming-soon',
			'title' => esc_html__( 'Coming soon mode is on', 'aero-dallas' ),
			'href'  => current_user_can( 'customize' ) ? admin_url( 'customize.php?autofocus[section]=aerodallas_coming_soon' ) : add_query_arg( AERODALLAS_CS_PREVIEW, '1', home_url( '/' ) ),
			'meta'  => array( 'class' => 'aerodallas-cs-flag' ),
		)
	);
}
add_action( 'admin_bar_menu', 'aerodallas_cs_admin_bar', 100 );

/**
 * Colour the admin bar reminder so it is hard to miss.
 */
function aerodallas_cs_admin_bar_style() {
	if ( ! is_admin_bar_showing() || ! aerodallas_cs_active() ) {
		return;
	}
	echo '<style>#wpadminbar .aerodallas-cs-flag>.ab-item{background:#fe7620;color:#152d63;font-weight:600}#wpadminbar .aerodallas-cs-flag>.ab-item:hover,#wpadminbar .aerodallas-cs-flag>.ab-item:focus{background:#ff8b40;color:#152d63}</style>' . "\n";
}
add_action( 'wp_head', 'aerodallas_cs_admin_bar_style' );
add_action( 'admin_head', 'aerodallas_cs_admin_bar_style' );
