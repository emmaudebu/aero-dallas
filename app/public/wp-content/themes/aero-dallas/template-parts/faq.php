<?php
/**
 * FAQ accordion built on <details>.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_faqs = aerodallas_items( 'aerodallas_faq' );
if ( ! $aerodallas_faqs ) {
	return;
}
$aerodallas_heading = $args['heading'] ?? __( 'Frequently Asked Questions', 'aero-dallas' );
?>
<section class="section section--mist faq" id="faq" aria-labelledby="faq-title">
	<div class="container faq__grid">
		<div class="faq__intro" data-reveal>
			<?php if ( ! empty( $args['index'] ) ) : ?>
				<p class="section-index"><span><?php echo esc_html( $args['index'] ); ?></span> <?php esc_html_e( 'FAQ', 'aero-dallas' ); ?></p>
			<?php endif; ?>
			<h2 class="section-title" id="faq-title"><?php echo esc_html( $aerodallas_heading ); ?></h2>
			<?php if ( aerodallas_detail( 'phone' ) ) : ?>
				<p class="faq__aside">
					<?php esc_html_e( 'Have a different question?', 'aero-dallas' ); ?>
					<a href="<?php echo esc_url( aerodallas_phone_href() ); ?>">
						<?php
						/* translators: %s: phone number. */
						echo esc_html( sprintf( __( 'Call %s', 'aero-dallas' ), aerodallas_detail( 'phone' ) ) );
						?>
					</a>
				</p>
			<?php endif; ?>
		</div>
		<div class="faq__list" data-reveal>
			<?php foreach ( $aerodallas_faqs as $aerodallas_faq ) : ?>
				<details class="faq__item">
					<summary>
						<span><?php echo esc_html( get_the_title( $aerodallas_faq ) ); ?></span>
						<?php echo aerodallas_icon( 'plus', 'faq__toggle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</summary>
					<div class="faq__answer">
						<?php echo wp_kses_post( wpautop( $aerodallas_faq->post_content ) ); ?>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
