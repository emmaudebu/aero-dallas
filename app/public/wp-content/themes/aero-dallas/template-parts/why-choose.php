<?php
/**
 * "Why Choose AERO DALLAS?": icon row of reasons and a circular photo cluster.
 *
 * Args: heading, media (bool, photo cluster), mist (bool, tinted background).
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_reasons = aerodallas_items( 'aerodallas_reason' );
if ( ! $aerodallas_reasons ) {
	return;
}
$aerodallas_heading = $args['heading'] ?? __( 'Why Choose AERO DALLAS?', 'aero-dallas' );
$aerodallas_media   = $args['media'] ?? true;
$aerodallas_icons   = array( 'briefcase', 'gear', 'shield', 'target', 'chat', 'headset' );
?>
<section class="section why<?php echo ! empty( $args['mist'] ) ? ' section--alt' : ''; ?><?php echo $aerodallas_media ? '' : ' why--plain'; ?>" aria-labelledby="why-title">
	<div class="container why__grid">
		<div class="why__body" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'Why Choose Us', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="why-title"><?php echo aerodallas_highlight( $aerodallas_heading, 'AERO DALLAS?' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h2>
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
			<div class="circles" data-reveal aria-hidden="true">
				<div class="circles__main"><?php echo aerodallas_image( 0, 'service-industrial', array( 'sizes' => '(min-width: 1024px) 420px, 80vw', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<div class="circles__small circles__small--a"><?php echo aerodallas_image( 0, 'service-aviation', array( 'sizes' => '200px', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<div class="circles__small circles__small--b"><?php echo aerodallas_image( 0, 'service-parts', array( 'sizes' => '200px', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			</div>
		<?php endif; ?>
	</div>
</section>
