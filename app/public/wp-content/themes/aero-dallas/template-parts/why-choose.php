<?php
/**
 * "Why Choose AERO DALLAS?" points.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_reasons = aerodallas_items( 'aerodallas_reason' );
if ( ! $aerodallas_reasons ) {
	return;
}
$aerodallas_heading = $args['heading'] ?? __( 'Why Choose AERO DALLAS?', 'aero-dallas' );
?>
<section class="section why" aria-labelledby="why-title">
	<div class="container why__grid">
		<div class="why__intro" data-reveal>
			<?php if ( ! empty( $args['index'] ) ) : ?>
				<p class="section-index"><span><?php echo esc_html( $args['index'] ); ?></span> <?php esc_html_e( 'Why us', 'aero-dallas' ); ?></p>
			<?php endif; ?>
			<h2 class="section-title" id="why-title"><?php echo esc_html( $aerodallas_heading ); ?></h2>
			<div class="why__media">
				<?php echo aerodallas_image( 0, 'detail-engine', array( 'sizes' => '(min-width: 1024px) 34vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<ul class="why__list">
			<?php foreach ( $aerodallas_reasons as $aerodallas_i => $aerodallas_reason ) : ?>
				<li class="why__item" data-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $aerodallas_i * 60 ) ); ?>ms">
					<span class="why__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $aerodallas_i + 1 ) ); ?></span>
					<?php echo aerodallas_icon( 'check-ring', 'why__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="why__text"><?php echo esc_html( get_the_title( $aerodallas_reason ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
