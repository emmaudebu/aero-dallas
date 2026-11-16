<?php
/**
 * Client testimonials. Hidden until at least one is published under
 * Site Content → Testimonials.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_items = array_slice( aerodallas_items( 'aerodallas_testimonial' ), 0, 6 );
if ( ! $aerodallas_items ) {
	return;
}
?>
<section class="section section--alt" aria-labelledby="testimonials-title">
	<div class="container">
		<header class="section-head" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'Testimonials', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="testimonials-title"><?php echo esc_html( $args['heading'] ?? __( 'What Our Clients Say', 'aero-dallas' ) ); ?></h2>
		</header>
		<ul class="card-grid card-grid--3">
			<?php foreach ( $aerodallas_items as $aerodallas_item ) : ?>
				<li class="quote-card" data-reveal>
					<?php echo aerodallas_icon( 'quote', 'quote-card__mark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<blockquote class="quote-card__text"><?php echo wp_kses_post( wpautop( $aerodallas_item->post_content ) ); ?></blockquote>
					<p class="quote-card__person">
						<?php if ( has_post_thumbnail( $aerodallas_item ) ) : ?>
							<?php echo get_the_post_thumbnail( $aerodallas_item, 'thumbnail', array( 'class' => 'quote-card__photo', 'alt' => '' ) ); ?>
						<?php endif; ?>
						<span>
							<strong><?php echo esc_html( get_the_title( $aerodallas_item ) ); ?></strong>
							<?php $aerodallas_role = (string) get_post_meta( $aerodallas_item->ID, '_aerodallas_role', true ); ?>
							<?php if ( $aerodallas_role ) : ?>
								<span class="quote-card__role"><?php echo esc_html( $aerodallas_role ); ?></span>
							<?php endif; ?>
						</span>
					</p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
