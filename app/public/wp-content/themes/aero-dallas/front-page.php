<?php
/**
 * Home page.
 *
 * Light mode: bright hero + floating quick-quote bar.
 * Dark mode:  dark hero + feature strip.
 * Both come from the same markup; CSS shows the right one for the mode.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

if ( 'page' !== get_option( 'show_on_front' ) ) {
	require get_index_template();
	return;
}

get_header();
the_post();
$aerodallas_services = aerodallas_items( 'aerodallas_service' );
?>

<section class="hero" aria-labelledby="hero-title">
	<div class="hero__bg" aria-hidden="true">
		<?php echo aerodallas_image( (int) get_theme_mod( 'aerodallas_hero_image', 0 ), 'hero-jet', array( 'sizes' => '100vw', 'eager' => true, 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
	</div>
	<div class="container hero__inner">
		<div class="hero__content">
			<?php aerodallas_eyebrow( aerodallas_field( 'hero_eyebrow' ) ); ?>
			<h1 class="hero__title" id="hero-title"><?php echo aerodallas_highlight( aerodallas_field( 'hero_heading' ), aerodallas_field( 'hero_highlight' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h1>
			<?php if ( aerodallas_field( 'hero_lead' ) ) : ?>
				<p class="hero__lead"><?php echo esc_html( aerodallas_field( 'hero_lead' ) ); ?></p>
			<?php endif; ?>
			<div class="hero__actions">
				<a class="btn btn--primary btn--lg" href="<?php echo esc_url( aerodallas_quote_url() ); ?>"><?php esc_html_e( 'Get a Quote', 'aero-dallas' ); ?></a>
				<a class="hero__explore" href="<?php echo esc_url( aerodallas_page_url( 'services' ) ); ?>">
					<span class="hero__explore-icon"><?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php esc_html_e( 'Explore Our Services', 'aero-dallas' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/quick-quote' ); ?>

<?php if ( $aerodallas_services ) : ?>
<section class="feature-strip" aria-label="<?php esc_attr_e( 'Service areas', 'aero-dallas' ); ?>">
	<ul class="container feature-strip__list">
		<?php foreach ( $aerodallas_services as $aerodallas_service ) : ?>
			<?php
			$aerodallas_card = (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_card_title', true );
			$aerodallas_card = $aerodallas_card ? $aerodallas_card : get_the_title( $aerodallas_service );
			?>
			<li class="feature-strip__item">
				<span class="icon-badge"><?php echo aerodallas_icon( (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_icon', true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<div>
					<h2 class="feature-strip__title"><a href="<?php echo esc_url( get_permalink( $aerodallas_service ) ); ?>"><?php echo esc_html( $aerodallas_card ); ?></a></h2>
					<p><?php echo esc_html( get_the_excerpt( $aerodallas_service ) ); ?></p>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</section>

<section class="section" aria-labelledby="services-title">
	<div class="container">
		<header class="section-head section-head--split" data-reveal>
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Our Services', 'aero-dallas' ); ?></p>
				<h2 class="section-title" id="services-title"><?php echo esc_html( aerodallas_field( 'services_heading' ) ); ?></h2>
				<?php if ( aerodallas_field( 'hero_text' ) ) : ?>
					<p class="section-intro"><?php echo esc_html( aerodallas_field( 'hero_text' ) ); ?></p>
				<?php endif; ?>
			</div>
			<a class="link-arrow" href="<?php echo esc_url( aerodallas_page_url( 'services' ) ); ?>"><?php esc_html_e( 'View All Services', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		</header>
		<ul class="service-grid">
			<?php
			foreach ( $aerodallas_services as $aerodallas_index => $aerodallas_service ) {
				get_template_part(
					'template-parts/service-card',
					null,
					array(
						'post'  => $aerodallas_service,
						'index' => $aerodallas_index,
					)
				);
			}
			?>
		</ul>
	</div>
</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/why-choose', null, array( 'heading' => aerodallas_field( 'why_heading' ) ) );

get_template_part(
	'template-parts/process-steps',
	null,
	array(
		'variant' => 'band',
		'heading' => aerodallas_field( 'process_heading' ),
		'intro'   => aerodallas_field( 'process_text' ),
	)
);

get_template_part( 'template-parts/who-we-are' );
get_template_part( 'template-parts/stats' );
get_template_part( 'template-parts/testimonials', null, array( 'heading' => aerodallas_field( 'testimonials_heading' ) ) );
get_template_part( 'template-parts/insights', null, array( 'heading' => aerodallas_field( 'insights_heading' ) ) );
get_template_part( 'template-parts/faq', null, array( 'heading' => aerodallas_field( 'faq_heading' ) ) );
get_template_part( 'template-parts/cta-band' );

get_footer();
