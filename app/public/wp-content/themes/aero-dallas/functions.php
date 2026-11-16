<?php
/**
 * AERO DALLAS theme bootstrap. Loads the modules in /inc.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

define( 'AERODALLAS_VERSION', '1.1.0' );
define( 'AERODALLAS_DIR', get_template_directory() );
define( 'AERODALLAS_URI', get_template_directory_uri() );

$aerodallas_modules = array(
	'helpers',
	'icons',
	'setup',
	'enqueue',
	'content-types',
	'meta-boxes',
	'customizer',
	'cpt-quote-request',
	'quote-form',
	'seo-schema',
	'service-content',
	'activation',
);

foreach ( $aerodallas_modules as $aerodallas_module ) {
	require_once AERODALLAS_DIR . '/inc/' . $aerodallas_module . '.php';
}
unset( $aerodallas_modules, $aerodallas_module );
