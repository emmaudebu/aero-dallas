<?php
/**
 * Theme supports, menus, image sizes and small front-end clean-ups.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and menus.
 */
function aerodallas_setup() {
	load_theme_textdomain( 'aero-dallas', AERODALLAS_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 360,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_editor_style( 'assets/css/editor.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'aero-dallas' ),
			'footer'  => __( 'Footer quick links', 'aero-dallas' ),
		)
	);

	add_image_size( 'aerodallas-card', 800, 533, true );
	add_image_size( 'aerodallas-wide', 1600, 0, false );
}
add_action( 'after_setup_theme', 'aerodallas_setup' );

/**
 * Only load the CSS of core blocks that are actually on the page.
 */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Remove emoji detection script and styles (not used by the theme).
 */
function aerodallas_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'aerodallas_disable_emojis' );

/**
 * Add a "no-js" class hook and template-specific body classes.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function aerodallas_body_class( $classes ) {
	if ( is_page_template() ) {
		$classes[] = 'tpl-' . sanitize_html_class( basename( get_page_template_slug(), '.php' ) );
	}
	return $classes;
}
add_filter( 'body_class', 'aerodallas_body_class' );

/**
 * Menu fallback when no menu is assigned: list the theme pages in order.
 *
 * @param array $args wp_nav_menu() arguments.
 */
function aerodallas_menu_fallback( $args ) {
	$labels = array(
		'home'     => __( 'Home', 'aero-dallas' ),
		'about'    => __( 'About Us', 'aero-dallas' ),
		'services' => __( 'Services', 'aero-dallas' ),
		'process'  => __( 'How We Work', 'aero-dallas' ),
		'policies' => __( 'Policies', 'aero-dallas' ),
		'contact'  => __( 'Contact Us', 'aero-dallas' ),
	);
	$class = ! empty( $args['menu_class'] ) ? $args['menu_class'] : 'menu';
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $labels as $key => $label ) {
		$id      = aerodallas_page_id( $key );
		$current = ( 'home' === $key && is_front_page() ) || ( $id && is_page( $id ) && ! is_front_page() );
		printf(
			'<li class="menu-item%3$s"><a href="%1$s"%4$s>%2$s</a></li>',
			esc_url( aerodallas_page_url( $key ) ),
			esc_html( $label ),
			$current ? ' current-menu-item' : '',
			$current ? ' aria-current="page"' : ''
		);
	}
	echo '</ul>';
}
