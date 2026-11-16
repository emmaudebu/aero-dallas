<?php
/**
 * Inner page header: photo background under a navy gradient, breadcrumb,
 * eyebrow, heading and optional lead. A featured image on the page
 * replaces the default photo.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_title   = get_the_title();
$aerodallas_heading = (string) ( $args['heading'] ?? '' );
$aerodallas_heading = '' !== trim( $aerodallas_heading ) ? $aerodallas_heading : $aerodallas_title;
$aerodallas_eyebrow = (string) ( $args['eyebrow'] ?? '' );
$aerodallas_lead    = (string) ( $args['lead'] ?? '' );
$aerodallas_photos  = array(
	'about'    => 'about-welding',
	'services' => 'service-aviation',
	'process'  => 'band-night-gate',
	'contact'  => 'band-truck-dusk',
);
$aerodallas_photo = $aerodallas_photos[ aerodallas_template_key( (int) get_the_ID() ) ] ?? '';
$aerodallas_thumb = (int) get_post_thumbnail_id();
?>
<header class="page-header<?php echo ( $aerodallas_photo || $aerodallas_thumb ) ? ' page-header--photo' : ''; ?>">
	<?php if ( $aerodallas_photo || $aerodallas_thumb ) : ?>
		<div class="band__bg" aria-hidden="true">
			<?php echo aerodallas_image( $aerodallas_thumb, $aerodallas_photo, array( 'sizes' => '100vw', 'eager' => true, 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>
	<div class="container page-header__inner">
		<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aero-dallas' ); ?>">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aero-dallas' ); ?></a></li>
				<li aria-current="page"><?php echo esc_html( $aerodallas_title ); ?></li>
			</ol>
		</nav>
		<?php aerodallas_eyebrow( $aerodallas_eyebrow, 'eyebrow--on-dark' ); ?>
		<h1 class="page-header__title"><?php echo esc_html( $aerodallas_heading ); ?></h1>
		<?php if ( $aerodallas_lead ) : ?>
			<p class="page-header__lead"><?php echo esc_html( $aerodallas_lead ); ?></p>
		<?php endif; ?>
	</div>
</header>
