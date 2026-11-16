<?php
/**
 * Home page.
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
$aerodallas_id       = get_the_ID();
$aerodallas_services = aerodallas_items( 'aerodallas_service' );
$aerodallas_phone    = aerodallas_detail( 'phone' );
?>

<section class="hero" aria-labelledby="hero-title">
	<div class="hero__grid" aria-hidden="true"></div>
	<div class="container hero__inner">
		<div class="hero__content">
			<?php aerodallas_eyebrow( aerodallas_field( 'hero_eyebrow' ) ); ?>
			<h1 class="hero__title" id="hero-title"><?php echo esc_html( aerodallas_field( 'hero_heading' ) ); ?></h1>
			<?php if ( aerodallas_field( 'hero_lead' ) ) : ?>
				<p class="hero__lead"><?php echo esc_html( aerodallas_field( 'hero_lead' ) ); ?></p>
			<?php endif; ?>
			<?php if ( aerodallas_field( 'hero_text' ) ) : ?>
				<p class="hero__text"><?php echo esc_html( aerodallas_field( 'hero_text' ) ); ?></p>
			<?php endif; ?>
			<div class="hero__actions">
				<a class="btn btn--primary btn--lg" href="<?php echo esc_url( aerodallas_quote_url() ); ?>"><?php esc_html_e( 'Request a Quote', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				<a class="btn btn--outline btn--lg" href="<?php echo esc_url( aerodallas_page_url( 'services' ) ); ?>"><?php esc_html_e( 'Explore Our Services', 'aero-dallas' ); ?></a>
				<a class="btn btn--link" href="<?php echo esc_url( aerodallas_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Contact Us', 'aero-dallas' ); ?></a>
			</div>
		</div>
		<div class="hero__media">
			<div class="hero__frame">
				<?php echo aerodallas_image( (int) get_theme_mod( 'aerodallas_hero_image', 0 ), 'hero-ground-power', array( 'sizes' => '(min-width: 1200px) 520px, 100vw', 'eager' => true, 'class' => 'hero__img' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
			</div>
			<?php if ( $aerodallas_services ) : ?>
				<ul class="hero__tags" aria-label="<?php esc_attr_e( 'Service areas', 'aero-dallas' ); ?>">
					<?php foreach ( $aerodallas_services as $aerodallas_service ) : ?>
						<li><?php echo aerodallas_icon( (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_icon', true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( get_post_meta( $aerodallas_service->ID, '_aerodallas_card_title', true ) ? get_post_meta( $aerodallas_service->ID, '_aerodallas_card_title', true ) : get_the_title( $aerodallas_service ) ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
	<div class="hero__swoosh" aria-hidden="true"><?php get_template_part( 'template-parts/swoosh' ); ?></div>
</section>

<?php if ( $aerodallas_services ) : ?>
<section class="section" aria-labelledby="services-title">
	<div class="container">
		<header class="section-head section-head--split" data-reveal>
			<div>
				<p class="section-index"><span>01</span> <?php esc_html_e( 'Services', 'aero-dallas' ); ?></p>
				<h2 class="section-title" id="services-title"><?php echo esc_html( aerodallas_field( 'services_heading' ) ); ?></h2>
			</div>
			<a class="btn btn--link" href="<?php echo esc_url( aerodallas_page_url( 'services' ) ); ?>"><?php esc_html_e( 'All services', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
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

<section class="section section--mist" aria-labelledby="about-title">
	<div class="container split split--media-left">
		<div class="split__media" data-reveal>
			<div class="framed framed--offset">
				<?php echo aerodallas_image( (int) get_theme_mod( 'aerodallas_about_image', 0 ), 'about-welding', array( 'sizes' => '(min-width: 1024px) 45vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<div class="split__body" data-reveal>
			<p class="section-index"><span>02</span> <?php esc_html_e( 'About', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="about-title"><?php echo esc_html( aerodallas_field( 'about_heading' ) ); ?></h2>
			<div class="prose">
				<?php echo wp_kses_post( wpautop( aerodallas_field( 'about_text' ) ) ); ?>
			</div>
			<a class="btn btn--outline" href="<?php echo esc_url( aerodallas_page_url( 'about' ) ); ?>"><?php esc_html_e( 'More about AERO DALLAS', 'aero-dallas' ); ?></a>
		</div>
	</div>
</section>

<?php
get_template_part(
	'template-parts/process-steps',
	null,
	array(
		'variant' => 'compact',
		'index'   => '03',
		'heading' => aerodallas_field( 'process_heading' ),
		'intro'   => aerodallas_field( 'process_text' ),
	)
);

get_template_part(
	'template-parts/why-choose',
	null,
	array(
		'index'   => '04',
		'heading' => aerodallas_field( 'why_heading' ),
	)
);

get_template_part(
	'template-parts/faq',
	null,
	array(
		'index'   => '05',
		'heading' => aerodallas_field( 'faq_heading' ),
	)
);

get_template_part( 'template-parts/cta-band' );

get_footer();
