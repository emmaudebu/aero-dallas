<?php
/**
 * Home page service card: photo, round icon badge, title, text, link.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_post = $args['post'] ?? null;
if ( ! $aerodallas_post instanceof WP_Post ) {
	return;
}
$aerodallas_index  = (int) ( $args['index'] ?? 0 );
$aerodallas_anchor = (string) get_post_meta( $aerodallas_post->ID, '_aerodallas_anchor', true );
$aerodallas_title  = (string) get_post_meta( $aerodallas_post->ID, '_aerodallas_card_title', true );
$aerodallas_title  = $aerodallas_title ? $aerodallas_title : get_the_title( $aerodallas_post );
$aerodallas_icon   = (string) get_post_meta( $aerodallas_post->ID, '_aerodallas_icon', true );
?>
<li class="service-card" data-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $aerodallas_index * 80 ) ); ?>ms">
	<div class="service-card__media">
		<?php echo aerodallas_image( (int) get_post_thumbnail_id( $aerodallas_post ), (string) get_post_meta( $aerodallas_post->ID, '_aerodallas_photo', true ), array( 'sizes' => '(min-width: 1100px) 300px, (min-width: 640px) 45vw, 100vw', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<div class="service-card__body">
		<?php if ( $aerodallas_icon ) : ?>
			<span class="service-card__icon"><?php echo aerodallas_icon( $aerodallas_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<?php endif; ?>
		<h3 class="service-card__title">
			<a href="<?php echo esc_url( aerodallas_page_url( 'services', $aerodallas_anchor ) ); ?>"><?php echo esc_html( $aerodallas_title ); ?></a>
		</h3>
		<?php if ( has_excerpt( $aerodallas_post ) ) : ?>
			<p><?php echo esc_html( get_the_excerpt( $aerodallas_post ) ); ?></p>
		<?php endif; ?>
		<span class="service-card__more" aria-hidden="true"><?php esc_html_e( 'Learn More', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	</div>
</li>
