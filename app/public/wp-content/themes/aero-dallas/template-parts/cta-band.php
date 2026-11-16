<?php
/**
 * Quote call-to-action band over a photo.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_heading = aerodallas_cta( 'cta_heading' );
$aerodallas_text    = aerodallas_cta( 'cta_text' );
$aerodallas_phone   = aerodallas_detail( 'phone' );
?>
<section class="cta-band" aria-labelledby="cta-title">
	<div class="band__bg" aria-hidden="true">
		<?php echo aerodallas_image( (int) get_theme_mod( 'aerodallas_cta_image', 0 ), 'band-truck-dusk', array( 'sizes' => '100vw', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<div class="container cta-band__inner" data-reveal>
		<div class="cta-band__copy">
			<h2 class="cta-band__title" id="cta-title"><?php echo esc_html( $aerodallas_heading ); ?></h2>
			<?php if ( $aerodallas_text ) : ?>
				<p><?php echo esc_html( $aerodallas_text ); ?></p>
			<?php endif; ?>
			<div class="cta-band__actions">
				<a class="btn btn--primary btn--lg" href="<?php echo esc_url( aerodallas_quote_url( $args['service'] ?? '' ) ); ?>"><?php esc_html_e( 'Get a Quote', 'aero-dallas' ); ?></a>
				<?php if ( $aerodallas_phone ) : ?>
					<a class="cta-band__phone" href="<?php echo esc_url( aerodallas_phone_href() ); ?>">
						<span class="cta-band__phone-icon"><?php echo aerodallas_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php echo esc_html( $aerodallas_phone ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
