<?php
/**
 * Row of cards overlapping the bottom of a page hero.
 *
 * Args:
 * - label (string) accessible name of the panel
 * - items (array of array{title:string,text?:string,url?:string,icon?:string,badge?:string,external?:bool})
 * - cols (int) columns on wide screens (2–4); default 4
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_items = $args['items'] ?? array();
if ( ! $aerodallas_items ) {
	return;
}
$aerodallas_cols = max( 2, min( 4, (int) ( $args['cols'] ?? 4 ) ) );
?>
<div class="hero-panel">
	<div class="container">
		<ul class="hero-panel__list hero-panel__list--<?php echo esc_attr( (string) $aerodallas_cols ); ?>" aria-label="<?php echo esc_attr( (string) ( $args['label'] ?? '' ) ); ?>">
			<?php foreach ( $aerodallas_items as $aerodallas_item ) : ?>
				<li class="hero-panel__item<?php echo ! empty( $aerodallas_item['url'] ) ? ' hero-panel__item--link' : ''; ?>">
					<?php if ( ! empty( $aerodallas_item['badge'] ) ) : ?>
						<span class="hero-panel__badge" aria-hidden="true"><?php echo esc_html( $aerodallas_item['badge'] ); ?></span>
					<?php elseif ( ! empty( $aerodallas_item['icon'] ) ) : ?>
						<span class="icon-badge"><?php echo aerodallas_icon( $aerodallas_item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
					<div class="hero-panel__text">
						<?php if ( ! empty( $aerodallas_item['url'] ) ) : ?>
							<a class="hero-panel__title" href="<?php echo esc_url( $aerodallas_item['url'] ); ?>"<?php echo ! empty( $aerodallas_item['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $aerodallas_item['title'] ); ?><?php echo ! empty( $aerodallas_item['external'] ) ? '<span class="screen-reader-text"> ' . esc_html__( '(opens in a new tab)', 'aero-dallas' ) . '</span>' : ''; ?></a>
						<?php else : ?>
							<span class="hero-panel__title"><?php echo esc_html( $aerodallas_item['title'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $aerodallas_item['text'] ) ) : ?>
							<span class="hero-panel__desc"><?php echo esc_html( $aerodallas_item['text'] ); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $aerodallas_item['url'] ) ) : ?>
						<?php echo aerodallas_icon( 'arrow-right', 'hero-panel__arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
