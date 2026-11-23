<?php
/**
 * "Why Choose AERO DALLAS INDUSTRIAL?": icon row of reasons and a photo mosaic.
 *
 * Args: heading, media (bool, photo mosaic), mist (bool, tinted background).
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_reasons = aerodallas_items( 'aerodallas_reason' );
if ( ! $aerodallas_reasons ) {
	return;
}
$aerodallas_heading = $args['heading'] ?? __( 'Why Choose AERO DALLAS INDUSTRIAL?', 'aero-dallas' );
$aerodallas_media   = $args['media'] ?? true;
$aerodallas_icons   = array( 'briefcase', 'gear', 'shield', 'target', 'chat', 'headset' );
?>
<section class="section why<?php echo ! empty( $args['mist'] ) ? ' section--alt' : ''; ?><?php echo $aerodallas_media ? '' : ' why--plain'; ?>" aria-labelledby="why-title">
	<div class="container why__grid">
		<div class="why__body" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'Why Choose Us', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="why-title"><?php echo aerodallas_highlight( $aerodallas_heading, 'AERO DALLAS INDUSTRIAL?' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h2>
			<ul class="why__list">
				<?php foreach ( $aerodallas_reasons as $aerodallas_i => $aerodallas_reason ) : ?>
					<li class="why__item">
						<span class="icon-ring"><?php echo aerodallas_icon( aerodallas_item_icon( $aerodallas_reason->ID, $aerodallas_i, $aerodallas_icons ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="why__text"><?php echo esc_html( get_the_title( $aerodallas_reason ) ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( ! is_page_template( 'page-templates/about.php' ) ) : ?>
				<a class="btn btn--primary" href="<?php echo esc_url( aerodallas_page_url( 'about' ) ); ?>"><?php esc_html_e( 'Discover More', 'aero-dallas' ); ?></a>
			<?php endif; ?>
		</div>
		<?php if ( $aerodallas_media ) : ?>
			<?php
			get_template_part(
				'template-parts/photo-mosaic',
				null,
				array(
					'photos' => array(
						array(
							'photo' => 'eq-air-start-unit',
							'label' => __( 'Air start units', 'aero-dallas' ),
						),
						array(
							'photo' => 'eq-cnc-milling',
							'label' => __( 'CNC machining', 'aero-dallas' ),
						),
						array(
							'photo' => 'eq-tow-bar',
							'label' => __( 'Aircraft tow bars', 'aero-dallas' ),
						),
					),
				)
			);
			?>
		<?php endif; ?>
	</div>
</section>
