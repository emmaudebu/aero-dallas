<?php
/**
 * Equipment catalogue: photo cards linked to the quote form with the service
 * and equipment filled in.
 *
 * Args:
 * - service (string) service anchor: that service's equipment; "parts-supply"
 *   shows everything, with All / Aviation / Industrial filters
 * - variant (string) "home": the featured items and a link to the full list
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_anchor = (string) ( $args['service'] ?? '' );
$aerodallas_home   = 'home' === ( $args['variant'] ?? '' );
$aerodallas_all    = 'parts-supply' === $aerodallas_anchor;
$aerodallas_items  = array_values(
	array_filter(
		aerodallas_equipment(),
		static function ( $item ) use ( $aerodallas_anchor, $aerodallas_all, $aerodallas_home ) {
			return $aerodallas_home ? $item['featured'] : ( $aerodallas_all || $item['service'] === $aerodallas_anchor );
		}
	)
);
if ( ! $aerodallas_items ) {
	return;
}
$aerodallas_categories = array(
	'aviation'   => __( 'Aviation', 'aero-dallas' ),
	'industrial' => __( 'Industrial', 'aero-dallas' ),
);
$aerodallas_counts     = array_count_values( wp_list_pluck( $aerodallas_items, 'service' ) );
$aerodallas_parts_url  = '';
foreach ( aerodallas_items( 'aerodallas_service' ) as $aerodallas_service ) {
	if ( 'parts-supply' === get_post_meta( $aerodallas_service->ID, '_aerodallas_anchor', true ) ) {
		$aerodallas_parts_url = get_permalink( $aerodallas_service ) . '#equipment';
	}
}
?>
<section class="section equipment<?php echo $aerodallas_home ? ' section--alt' : ''; ?>" id="equipment" aria-labelledby="equipment-title">
	<div class="container">
		<header class="section-head section-head--split" data-reveal>
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Equipment', 'aero-dallas' ); ?></p>
				<h2 class="section-title" id="equipment-title">
					<?php
					if ( $aerodallas_home ) {
						esc_html_e( 'Equipment We Supply & Support', 'aero-dallas' );
					} elseif ( $aerodallas_all ) {
						esc_html_e( 'Equipment We Supply', 'aero-dallas' );
					} else {
						esc_html_e( 'Equipment We Support', 'aero-dallas' );
					}
					?>
				</h2>
				<p class="section-intro"><?php esc_html_e( 'A selection of the aviation ground support and industrial equipment we work with. Tell us the make, model and specifications you need and we will prepare a quotation.', 'aero-dallas' ); ?></p>
			</div>
			<?php if ( $aerodallas_home && $aerodallas_parts_url ) : ?>
				<a class="link-arrow" href="<?php echo esc_url( $aerodallas_parts_url ); ?>"><?php esc_html_e( 'View All Equipment', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php else : ?>
				<a class="link-arrow" href="<?php echo esc_url( aerodallas_quote_url( $aerodallas_anchor ) ); ?>"><?php esc_html_e( 'Request a Quote', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php endif; ?>
		</header>

		<?php if ( $aerodallas_all && count( $aerodallas_counts ) > 1 ) : ?>
			<div class="equipment__filters" role="group" aria-label="<?php esc_attr_e( 'Show equipment', 'aero-dallas' ); ?>" hidden data-equipment-filters>
				<button type="button" aria-pressed="true" data-filter="all"><?php esc_html_e( 'All', 'aero-dallas' ); ?> <span class="equipment__count"><?php echo esc_html( (string) count( $aerodallas_items ) ); ?></span></button>
				<?php foreach ( $aerodallas_categories as $aerodallas_key => $aerodallas_name ) : ?>
					<?php if ( ! empty( $aerodallas_counts[ $aerodallas_key ] ) ) : ?>
						<button type="button" aria-pressed="false" data-filter="<?php echo esc_attr( $aerodallas_key ); ?>"><?php echo esc_html( $aerodallas_name ); ?> <span class="equipment__count"><?php echo esc_html( (string) $aerodallas_counts[ $aerodallas_key ] ); ?></span></button>
					<?php endif; ?>
				<?php endforeach; ?>
				<p class="screen-reader-text" role="status" data-equipment-status></p>
			</div>
		<?php endif; ?>

		<ul class="equipment__grid">
			<?php foreach ( $aerodallas_items as $aerodallas_i => $aerodallas_item ) : ?>
				<?php
				$aerodallas_url = add_query_arg(
					array(
						'service'   => $aerodallas_all ? 'parts-supply' : $aerodallas_item['service'],
						'equipment' => rawurlencode( $aerodallas_item['label'] ),
					),
					aerodallas_page_url( 'contact' )
				) . '#quote';
				?>
				<li class="equipment__item" data-category="<?php echo esc_attr( $aerodallas_item['service'] ); ?>" data-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( min( $aerodallas_i, 5 ) * 60 ) ); ?>ms">
					<a class="equipment__card equipment__card--<?php echo esc_attr( $aerodallas_item['fit'] ); ?>" href="<?php echo esc_url( $aerodallas_url ); ?>">
						<span class="equipment__media">
							<?php echo aerodallas_image( 0, $aerodallas_item['photo'], array( 'class' => 'equipment__img', 'sizes' => '(min-width: 1100px) 300px, (min-width: 640px) 50vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
							<span class="equipment__tag"><?php echo esc_html( $aerodallas_categories[ $aerodallas_item['service'] ] ?? '' ); ?></span>
						</span>
						<span class="equipment__body">
							<span class="equipment__title"><?php echo esc_html( $aerodallas_item['label'] ); ?></span>
							<span class="equipment__cta"><?php esc_html_e( 'Request a quote', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
