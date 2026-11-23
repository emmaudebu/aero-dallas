<?php
/**
 * Logo link.
 *
 * Variants:
 * - "auto":  navy wordmark in light mode, white wordmark in dark mode (CSS swaps them).
 * - "white": white wordmark, for areas that are always dark (footer, mobile menu).
 *
 * A logo uploaded in the Customizer replaces the navy wordmark.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_variant = ( $args['variant'] ?? 'auto' ) === 'white' ? 'white' : 'auto';
$aerodallas_label   = __( 'AERO DALLAS INDUSTRIAL, home', 'aero-dallas' );
$aerodallas_dark    = 'dark' === aerodallas_default_mode();
$aerodallas_img     = static function ( $file, $class, $lazy ) {
	printf(
		'<img class="%1$s" src="%2$s" width="553" height="185" alt=""%3$s>',
		esc_attr( $class ),
		esc_url( AERODALLAS_URI . '/assets/img/brand/' . $file ),
		$lazy ? ' loading="lazy"' : ''
	);
};
?>
<a class="brand brand--<?php echo esc_attr( $aerodallas_variant ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( $aerodallas_label ); ?>"<?php echo is_front_page() ? ' aria-current="page"' : ''; ?>>
	<?php
	if ( 'white' === $aerodallas_variant ) {
		$aerodallas_img( 'logo-main-white.webp', 'brand__img', true );
	} else {
		if ( has_custom_logo() ) {
			$aerodallas_logo_id = (int) get_theme_mod( 'custom_logo' );
			echo wp_get_attachment_image( $aerodallas_logo_id, 'full', false, array( 'class' => 'brand__img brand__img--light', 'alt' => '' ) );
		} else {
			$aerodallas_img( 'logo-main.webp', 'brand__img brand__img--light', $aerodallas_dark );
		}
		$aerodallas_img( 'logo-main-white.webp', 'brand__img brand__img--dark', ! $aerodallas_dark );
	}
	?>
</a>
