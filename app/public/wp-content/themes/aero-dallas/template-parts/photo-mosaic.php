<?php
/**
 * Three photos in rounded rectangles: one large on the left, two stacked on
 * the right, each with an optional caption.
 *
 * Args:
 * - photos (array of array{attachment?:int,photo:string,label?:string}) three photos
 * - flip   (bool) put the large photo on the right
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_photos = array_slice( (array) ( $args['photos'] ?? array() ), 0, 3 );
if ( ! $aerodallas_photos ) {
	return;
}
?>
<div class="mosaic<?php echo ! empty( $args['flip'] ) ? ' mosaic--flip' : ''; ?>" data-reveal>
	<?php foreach ( $aerodallas_photos as $aerodallas_i => $aerodallas_photo ) : ?>
		<figure class="mosaic__item mosaic__item--<?php echo esc_attr( sanitize_html_class( $aerodallas_photo['photo'] ) ); ?><?php echo 0 === $aerodallas_i ? ' mosaic__item--main' : ''; ?>">
			<?php echo aerodallas_image( (int) ( $aerodallas_photo['attachment'] ?? 0 ), $aerodallas_photo['photo'], array( 'sizes' => 0 === $aerodallas_i ? '(min-width: 1024px) 340px, 60vw' : '(min-width: 1024px) 260px, 40vw', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
			<?php if ( ! empty( $aerodallas_photo['label'] ) ) : ?>
				<figcaption class="mosaic__label"><?php echo esc_html( $aerodallas_photo['label'] ); ?></figcaption>
			<?php endif; ?>
		</figure>
	<?php endforeach; ?>
</div>
