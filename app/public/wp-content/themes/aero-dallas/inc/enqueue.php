<?php
/**
 * Styles, scripts and font preloads.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Version string for a theme asset, based on its modification time.
 *
 * @param string $rel Path relative to the theme root.
 * @return string
 */
function aerodallas_asset_version( $rel ) {
	$file = AERODALLAS_DIR . '/' . $rel;
	return file_exists( $file ) ? AERODALLAS_VERSION . '.' . filemtime( $file ) : AERODALLAS_VERSION;
}

/**
 * Enqueue front-end assets.
 */
function aerodallas_enqueue_assets() {
	wp_enqueue_style( 'aerodallas-main', AERODALLAS_URI . '/assets/css/main.css', array(), aerodallas_asset_version( 'assets/css/main.css' ) );

	wp_enqueue_script(
		'aerodallas-main',
		AERODALLAS_URI . '/assets/js/main.js',
		array(),
		aerodallas_asset_version( 'assets/js/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	if ( is_page_template( 'page-templates/contact.php' ) ) {
		wp_enqueue_script(
			'aerodallas-quote',
			AERODALLAS_URI . '/assets/js/quote-form.js',
			array(),
			aerodallas_asset_version( 'assets/js/quote-form.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
		wp_localize_script(
			'aerodallas-quote',
			'aerodallasQuote',
			array(
				'maxBytes' => AERODALLAS_QUOTE_MAX_BYTES,
				'i18n'     => array(
					'sending'      => __( 'Sending…', 'aero-dallas' ),
					'submit'       => __( 'Submit Request', 'aero-dallas' ),
					'network'      => __( 'We could not send your request. Please check your connection and try again, or call us.', 'aero-dallas' ),
					'tooLarge'     => __( 'This file is larger than 10 MB. Please choose a smaller file.', 'aero-dallas' ),
					'fileType'     => __( 'Please upload a PDF, Word (DOC, DOCX), Excel (XLS, XLSX), JPG or PNG file.', 'aero-dallas' ),
					'name'         => __( 'Please enter your full name.', 'aero-dallas' ),
					'email'        => __( 'Please enter your email address.', 'aero-dallas' ),
					'emailInvalid' => __( 'Please enter a valid email address, for example name@company.com.', 'aero-dallas' ),
					'phone'        => __( 'Please enter a phone number.', 'aero-dallas' ),
					'phoneInvalid' => __( 'Please enter a valid phone number, including the country code if outside the US.', 'aero-dallas' ),
					'service'      => __( 'Please choose the service you need.', 'aero-dallas' ),
					'description'  => __( 'Please describe your project or requirements.', 'aero-dallas' ),
					/* translators: %d: number of fields (1). */
					'oneField'     => _n( 'Please correct %d field below and submit again.', 'Please correct %d fields below and submit again.', 1, 'aero-dallas' ),
					/* translators: %d: number of fields (2 or more). */
					'manyFields'   => _n( 'Please correct %d field below and submit again.', 'Please correct %d fields below and submit again.', 2, 'aero-dallas' ),
				),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'aerodallas_enqueue_assets' );

/**
 * Preload the two most-used font files, and mark the document as JS-capable
 * before first paint so reveal animations never flash.
 */
function aerodallas_head_early() {
	$fonts = array( 'open-sauce-one-400.woff2', 'archivo-black-400.woff2' );
	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( AERODALLAS_URI . '/assets/fonts/' . $font )
		);
	}
	// Set the colour mode before first paint: saved choice, else the site default.
	$aerodallas_mode_script = sprintf(
		'(function(d){var m=null,a=%2$s;try{if(a){m=localStorage.getItem("aerodallas-mode");}}catch(e){}if(m!=="light"&&m!=="dark"){m=%1$s;if(m==="system"){m=window.matchMedia&&matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light";}}d.setAttribute("data-theme",m);d.classList.add("js");})(document.documentElement);',
		wp_json_encode( aerodallas_default_mode() ),
		get_theme_mod( 'aerodallas_show_toggle', true ) ? 'true' : 'false'
	);
	echo '<script>' . $aerodallas_mode_script . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static script with JSON-encoded values.
	echo '<meta name="color-scheme" content="light dark">' . "\n";
}
add_action( 'wp_head', 'aerodallas_head_early', 1 );

/**
 * Site icon fallback when none is set in the Customizer.
 */
function aerodallas_favicon_fallback() {
	if ( has_site_icon() ) {
		return;
	}
	$base = AERODALLAS_URI . '/assets/img/brand/';
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( $base . 'icon.svg' ) );
	printf( '<link rel="icon" href="%s" sizes="32x32" type="image/png">' . "\n", esc_url( $base . 'icon-32.png' ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( $base . 'icon-180.png' ) );
	echo '<meta name="theme-color" content="#0A1430">' . "\n";
}
add_action( 'wp_head', 'aerodallas_favicon_fallback', 5 );

/**
 * Admin styles for the theme's meta boxes and content screens.
 */
function aerodallas_admin_assets() {
	wp_enqueue_style( 'aerodallas-admin', AERODALLAS_URI . '/assets/css/admin.css', array(), aerodallas_asset_version( 'assets/css/admin.css' ) );
}
add_action( 'admin_enqueue_scripts', 'aerodallas_admin_assets' );
