<?php
/**
 * Home "Who We Are": intro text, four core values with icons, and a
 * large circular photo with two smaller circles.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_values = array_slice( aerodallas_items( 'aerodallas_value' ), 0, 4 );
$aerodallas_icons  = array( 'shield', 'briefcase', 'target', 'gauge' );
?>
<section class="section who" aria-labelledby="who-title">
	<div class="container who__grid">
		<div class="who__body" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'About Us', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="who-title"><?php echo esc_html( aerodallas_field( 'about_heading' ) ); ?></h2>
			<div class="prose who__text">
				<?php echo wp_kses_post( wpautop( aerodallas_field( 'about_text' ) ) ); ?>
			</div>
			<?php if ( $aerodallas_values ) : ?>
				<ul class="mini-features">
					<?php foreach ( $aerodallas_values as $aerodallas_i => $aerodallas_value ) : ?>
						<li class="mini-features__item">
							<span class="icon-badge icon-badge--sm"><?php echo aerodallas_icon( aerodallas_item_icon( $aerodallas_value->ID, $aerodallas_i, $aerodallas_icons ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<div>
								<h3 class="mini-features__title"><?php echo esc_html( get_the_title( $aerodallas_value ) ); ?></h3>
								<?php if ( has_excerpt( $aerodallas_value ) ) : ?>
									<p><?php echo esc_html( get_the_excerpt( $aerodallas_value ) ); ?></p>
								<?php endif; ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<a class="btn btn--primary" href="<?php echo esc_url( aerodallas_page_url( 'about' ) ); ?>"><?php esc_html_e( 'More About Us', 'aero-dallas' ); ?></a>
		</div>
		<div class="circles circles--right" data-reveal aria-hidden="true">
			<div class="circles__main"><?php echo aerodallas_image( (int) get_theme_mod( 'aerodallas_about_image', 0 ), 'detail-engine', array( 'sizes' => '(min-width: 1024px) 420px, 80vw', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<div class="circles__small circles__small--a"><?php echo aerodallas_image( 0, 'about-welding', array( 'sizes' => '200px', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<div class="circles__small circles__small--b"><?php echo aerodallas_image( 0, 'service-logistics', array( 'sizes' => '200px', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</div>
</section>
