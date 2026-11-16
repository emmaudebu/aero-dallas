<?php
/**
 * Logo link. Uses the Customizer logo when set (default variant only),
 * otherwise the bundled SVG wordmark.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_variant = ( $args['variant'] ?? 'default' ) === 'white' ? 'white' : 'default';
$aerodallas_file    = 'white' === $aerodallas_variant ? 'logo-white.svg' : 'logo.svg';
$aerodallas_label   = __( 'AERO DALLAS — Industrial & Aviation Support, home', 'aero-dallas' );

if ( 'default' === $aerodallas_variant && has_custom_logo() ) {
	echo '<div class="brand">' . get_custom_logo() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core output.
	return;
}
?>
<a class="brand brand--<?php echo esc_attr( $aerodallas_variant ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"<?php echo is_front_page() ? ' aria-current="page"' : ''; ?>>
	<img src="<?php echo esc_url( AERODALLAS_URI . '/assets/img/brand/' . $aerodallas_file ); ?>" width="3246" height="1084" alt="<?php echo esc_attr( $aerodallas_label ); ?>">
</a>
