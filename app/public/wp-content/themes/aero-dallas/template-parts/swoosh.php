<?php
/**
 * The brand swoosh, inlined from assets/img/brand/swoosh.svg so it can be
 * sized and coloured with CSS.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_swoosh = AERODALLAS_DIR . '/assets/img/brand/swoosh.svg';
if ( ! file_exists( $aerodallas_swoosh ) ) {
	return;
}
$aerodallas_svg = (string) file_get_contents( $aerodallas_swoosh ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
$aerodallas_svg = str_replace( '<svg ', '<svg class="swoosh" aria-hidden="true" focusable="false" preserveAspectRatio="none" ', $aerodallas_svg );
$aerodallas_svg = str_replace( 'fill="#FE7620"', 'fill="currentColor"', $aerodallas_svg );
echo wp_kses(
	$aerodallas_svg,
	array(
		'svg'  => array(
			'class'               => true,
			'aria-hidden'         => true,
			'focusable'           => true,
			'preserveaspectratio' => true,
			'xmlns'               => true,
			'viewbox'             => true,
		),
		'path' => array(
			'd'    => true,
			'fill' => true,
		),
	)
);
