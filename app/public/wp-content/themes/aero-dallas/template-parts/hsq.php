<?php
/**
 * Health, Safety & Quality summary for the Home and About pages: the policy
 * introduction and commitment beside the six principles, with a link to the
 * full policy on the Policies page.
 *
 * Args:
 * - heading (string) section heading
 * - alt     (bool)   grey background
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_hsq     = aerodallas_hsq();
$aerodallas_heading = $args['heading'] ?? '';
$aerodallas_heading = '' !== $aerodallas_heading ? $aerodallas_heading : __( 'Quality Service Begins with Safe Operations', 'aero-dallas' );
?>
<section class="section hsq<?php echo ! empty( $args['alt'] ) ? ' section--alt' : ''; ?>" aria-labelledby="hsq-title">
	<div class="container hsq__grid">
		<div class="hsq__intro" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'Health, Safety & Quality', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="hsq-title"><?php echo aerodallas_highlight( $aerodallas_heading, __( 'Safe Operations', 'aero-dallas' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h2>
			<?php foreach ( $aerodallas_hsq['intro'] as $aerodallas_i => $aerodallas_text ) : ?>
				<p class="<?php echo 0 === $aerodallas_i ? 'section-intro' : 'hsq__text'; ?>"><?php echo esc_html( $aerodallas_text ); ?></p>
			<?php endforeach; ?>
			<blockquote class="hsq__commitment">
				<p class="hsq__commitment-label"><?php echo aerodallas_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Our Commitment', 'aero-dallas' ); ?></p>
				<p><?php echo esc_html( $aerodallas_hsq['commitment'] ); ?></p>
			</blockquote>
			<a class="btn btn--primary" href="<?php echo esc_url( aerodallas_page_url( 'policies' ) . '#health-safety-quality' ); ?>"><?php esc_html_e( 'Read Our Full Policy', 'aero-dallas' ); ?></a>
		</div>

		<div class="hsq__principles">
			<p class="hsq__lead" id="hsq-principles"><?php echo esc_html( $aerodallas_hsq['principles_intro'] ); ?></p>
			<ol class="hsq__list" aria-labelledby="hsq-principles">
				<?php foreach ( $aerodallas_hsq['principles'] as $aerodallas_i => $aerodallas_principle ) : ?>
					<li class="hsq__item" data-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $aerodallas_i * 60 ) ); ?>ms">
						<div class="hsq__item-head">
							<span class="icon-badge"><?php echo aerodallas_icon( $aerodallas_principle['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="hsq__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $aerodallas_i + 1 ) ); ?></span>
						</div>
						<h3 class="hsq__title"><?php echo esc_html( $aerodallas_principle['title'] ); ?></h3>
						<p><?php echo esc_html( $aerodallas_principle['text'][0] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
