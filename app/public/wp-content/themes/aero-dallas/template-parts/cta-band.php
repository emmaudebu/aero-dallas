<?php
/**
 * Quote call-to-action band.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_heading = aerodallas_cta( 'cta_heading' );
$aerodallas_text    = aerodallas_cta( 'cta_text' );
$aerodallas_phone   = aerodallas_detail( 'phone' );
?>
<section class="cta-band" aria-labelledby="cta-title">
	<div class="container cta-band__inner" data-reveal>
		<div class="cta-band__copy">
			<h2 class="cta-band__title" id="cta-title"><?php echo esc_html( $aerodallas_heading ); ?></h2>
			<?php if ( $aerodallas_text ) : ?>
				<p><?php echo esc_html( $aerodallas_text ); ?></p>
			<?php endif; ?>
		</div>
		<div class="cta-band__actions">
			<a class="btn btn--primary btn--lg" href="<?php echo esc_url( aerodallas_quote_url( $args['service'] ?? '' ) ); ?>"><?php esc_html_e( 'Request a Quote', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php if ( $aerodallas_phone ) : ?>
				<a class="cta-band__phone" href="<?php echo esc_url( aerodallas_phone_href() ); ?>">
					<?php echo aerodallas_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><span class="cta-band__phone-label"><?php esc_html_e( 'Or call', 'aero-dallas' ); ?></span> <?php echo esc_html( $aerodallas_phone ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
