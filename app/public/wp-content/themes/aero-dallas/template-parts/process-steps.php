<?php
/**
 * Numbered process steps.
 * Variants: "compact" (home page summary) and "timeline" (How We Work page).
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_steps = aerodallas_items( 'aerodallas_step' );
if ( ! $aerodallas_steps ) {
	return;
}
$aerodallas_variant = ( $args['variant'] ?? 'compact' ) === 'timeline' ? 'timeline' : 'compact';
$aerodallas_total   = count( $aerodallas_steps );

if ( 'timeline' === $aerodallas_variant ) :
	?>
	<ol class="timeline">
		<?php foreach ( $aerodallas_steps as $aerodallas_i => $aerodallas_step ) : ?>
			<?php $aerodallas_icon = (string) get_post_meta( $aerodallas_step->ID, '_aerodallas_icon', true ); ?>
			<li class="timeline__item" data-reveal>
				<div class="timeline__marker" aria-hidden="true">
					<span class="timeline__num"><?php echo esc_html( sprintf( '%02d', $aerodallas_i + 1 ) ); ?></span>
				</div>
				<div class="timeline__card">
					<p class="timeline__count">
						<?php
						/* translators: 1: step number, 2: total steps. */
						echo esc_html( sprintf( __( 'Step %1$d of %2$d', 'aero-dallas' ), $aerodallas_i + 1, $aerodallas_total ) );
						?>
					</p>
					<h2 class="timeline__title">
						<?php if ( $aerodallas_icon ) : ?>
							<span class="timeline__icon"><?php echo aerodallas_icon( $aerodallas_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php endif; ?>
						<?php echo esc_html( get_the_title( $aerodallas_step ) ); ?>
					</h2>
					<?php if ( has_excerpt( $aerodallas_step ) ) : ?>
						<p><?php echo esc_html( get_the_excerpt( $aerodallas_step ) ); ?></p>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ol>
	<?php
	return;
endif;
?>
<section class="section section--navy process-summary" aria-labelledby="process-title">
	<div class="container">
		<header class="section-head section-head--split" data-reveal>
			<div>
				<?php if ( ! empty( $args['index'] ) ) : ?>
					<p class="section-index"><span><?php echo esc_html( $args['index'] ); ?></span> <?php esc_html_e( 'Process', 'aero-dallas' ); ?></p>
				<?php endif; ?>
				<h2 class="section-title" id="process-title"><?php echo esc_html( $args['heading'] ?? __( 'How We Work', 'aero-dallas' ) ); ?></h2>
				<?php if ( ! empty( $args['intro'] ) ) : ?>
					<p class="section-intro"><?php echo esc_html( $args['intro'] ); ?></p>
				<?php endif; ?>
			</div>
			<a class="btn btn--ghost-light" href="<?php echo esc_url( aerodallas_page_url( 'process' ) ); ?>"><?php esc_html_e( 'See the full process', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		</header>
		<ol class="steps-row">
			<?php foreach ( $aerodallas_steps as $aerodallas_i => $aerodallas_step ) : ?>
				<li class="steps-row__item" data-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $aerodallas_i * 60 ) ); ?>ms">
					<span class="steps-row__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $aerodallas_i + 1 ) ); ?></span>
					<h3 class="steps-row__title"><?php echo esc_html( get_the_title( $aerodallas_step ) ); ?></h3>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
