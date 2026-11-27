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
$aerodallas_sectors  = aerodallas_hero_sectors();
$aerodallas_showcase = aerodallas_hero_showcase();
$aerodallas_buttons  = array(
	array( aerodallas_hero( 'hero_btn1_label' ), aerodallas_hero( 'hero_btn1_url' ), aerodallas_page_url( 'about' ) ),
	array( aerodallas_hero( 'hero_btn2_label' ), aerodallas_hero( 'hero_btn2_url' ), aerodallas_page_url( 'services' ) ),
);
$aerodallas_captions = (bool) aerodallas_hero( 'hero_captions' );
?>

<section class="hero hero--showcase" aria-labelledby="hero-title" style="<?php echo esc_attr( sprintf( '--hero-title: %dpx; --hero-title-phone: %dpx; --hero-lead: %dpx;', aerodallas_hero( 'hero_title_size' ), aerodallas_hero( 'hero_title_size_mobile' ), aerodallas_hero( 'hero_lead_size' ) ) ); ?>">
	<div class="hero__backdrop" aria-hidden="true">
		<span class="hero__glow hero__glow--a"></span>
		<span class="hero__glow hero__glow--b"></span>
	</div>
	<?php get_template_part( 'template-parts/flight-path' ); ?>
	<div class="container hero__inner">
		<div class="hero__content">
			<?php aerodallas_eyebrow( aerodallas_hero( 'hero_eyebrow' ) ); ?>
			<h1 class="hero__title" id="hero-title"><?php echo aerodallas_highlight( aerodallas_hero( 'hero_heading' ), aerodallas_hero( 'hero_highlight' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h1>
			<?php if ( aerodallas_hero( 'hero_lead' ) ) : ?>
				<p class="hero__lead"><?php echo esc_html( aerodallas_hero( 'hero_lead' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $aerodallas_buttons[0][0] || $aerodallas_buttons[1][0] ) : ?>
				<div class="hero__actions">
					<?php if ( $aerodallas_buttons[0][0] ) : ?>
						<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $aerodallas_buttons[0][1] ? $aerodallas_buttons[0][1] : $aerodallas_buttons[0][2] ); ?>"><?php echo esc_html( $aerodallas_buttons[0][0] ); ?></a>
					<?php endif; ?>
					<?php if ( $aerodallas_buttons[1][0] ) : ?>
						<a class="hero__explore" href="<?php echo esc_url( $aerodallas_buttons[1][1] ? $aerodallas_buttons[1][1] : $aerodallas_buttons[1][2] ); ?>">
							<span class="hero__explore-icon"><?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php echo esc_html( $aerodallas_buttons[1][0] ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $aerodallas_showcase ) : ?>
			<div class="showcase showcase--<?php echo esc_attr( aerodallas_hero( 'hero_effect' ) ); ?><?php echo aerodallas_hero( 'hero_thumbs' ) ? '' : ' showcase--no-thumbs'; ?>" data-showcase data-interval="<?php echo esc_attr( (string) ( (int) aerodallas_hero( 'hero_interval' ) * 1000 ) ); ?>" data-autoplay="<?php echo aerodallas_hero( 'hero_autoplay' ) ? '1' : '0'; ?>" style="<?php echo esc_attr( sprintf( '--showcase-speed: %sms;', (string) round( (float) aerodallas_hero( 'hero_speed' ) * 1000 ) ) ); ?>">
				<div class="showcase__stage">
					<?php foreach ( $aerodallas_showcase as $aerodallas_i => $aerodallas_item ) : ?>
						<figure class="showcase__slide showcase__slide--<?php echo esc_attr( $aerodallas_item['fit'] ); ?><?php echo $aerodallas_item['attachment'] ? '' : esc_attr( ' showcase__slide--' . $aerodallas_item['photo'] ); ?><?php echo 0 === $aerodallas_i ? ' is-active' : ''; ?>" data-showcase-slide>
							<span class="showcase__media">
								<?php echo aerodallas_image( $aerodallas_item['attachment'], $aerodallas_item['photo'], array( 'class' => 'showcase__img', 'sizes' => '(min-width: 1100px) 680px, 100vw', 'eager' => 0 === $aerodallas_i, 'alt' => $aerodallas_item['label'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
							</span>
							<?php if ( $aerodallas_captions && ( $aerodallas_item['label'] || $aerodallas_item['category'] ) ) : ?>
								<figcaption class="showcase__caption">
									<?php if ( $aerodallas_item['category'] ) : ?>
										<span class="showcase__tag"><?php echo esc_html( $aerodallas_item['category'] ); ?></span>
									<?php endif; ?>
									<?php if ( $aerodallas_item['label'] ) : ?>
										<span class="showcase__title"><?php echo esc_html( $aerodallas_item['label'] ); ?></span>
									<?php endif; ?>
									<?php if ( $aerodallas_item['url'] && aerodallas_hero( 'hero_link_text' ) ) : ?>
										<a class="showcase__link" href="<?php echo esc_url( $aerodallas_item['url'] ); ?>"><?php echo esc_html( aerodallas_hero( 'hero_link_text' ) ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"> <?php echo esc_html( $aerodallas_item['label'] ); ?></span></a>
									<?php endif; ?>
								</figcaption>
							<?php endif; ?>
						</figure>
					<?php endforeach; ?>
					<span class="showcase__progress" aria-hidden="true"></span>
				</div>
				<?php if ( count( $aerodallas_showcase ) > 1 ) : ?>
					<div class="showcase__controls" hidden data-showcase-controls>
						<ul class="showcase__thumbs" aria-label="<?php esc_attr_e( 'Equipment photos', 'aero-dallas' ); ?>">
							<?php foreach ( $aerodallas_showcase as $aerodallas_i => $aerodallas_item ) : ?>
								<li>
									<button class="showcase__thumb showcase__thumb--<?php echo esc_attr( $aerodallas_item['fit'] ); ?>" type="button" aria-current="<?php echo 0 === $aerodallas_i ? 'true' : 'false'; ?>" data-showcase-thumb>
										<?php echo aerodallas_image( $aerodallas_item['attachment'], $aerodallas_item['photo'], array( 'sizes' => '96px', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
										<span class="screen-reader-text">
											<?php
											/* translators: %s: equipment name. */
											echo esc_html( sprintf( __( 'Show %s', 'aero-dallas' ), $aerodallas_item['label'] ? $aerodallas_item['label'] : __( 'photo', 'aero-dallas' ) ) );
											?>
										</span>
									</button>
								</li>
							<?php endforeach; ?>
						</ul>
						<button class="showcase__pause" type="button" aria-pressed="false" data-showcase-pause>
							<?php echo aerodallas_icon( 'pause', 'showcase__icon-pause' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo aerodallas_icon( 'play', 'showcase__icon-play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="screen-reader-text"><?php esc_html_e( 'Pause the equipment slideshow', 'aero-dallas' ); ?></span>
						</button>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( count( $aerodallas_sectors ) > 1 ) : ?>
			<div class="hero-sectors">
				<p class="hero-sectors__title" id="hero-sectors-title"><?php esc_html_e( 'What we support', 'aero-dallas' ); ?></p>
				<ul class="hero-sectors__list" aria-labelledby="hero-sectors-title">
					<?php foreach ( $aerodallas_sectors as $aerodallas_sector ) : ?>
						<li class="hero-sectors__item">
							<a href="<?php echo esc_url( $aerodallas_sector['url'] ); ?>">
								<span class="hero-sectors__icon"><?php echo aerodallas_icon( $aerodallas_sector['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<span class="hero-sectors__label"><?php echo esc_html( $aerodallas_sector['label'] ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
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
get_template_part( 'template-parts/equipment-gallery', null, array( 'variant' => 'home' ) );
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
get_template_part(
	'template-parts/hsq',
	null,
	array(
		'heading' => aerodallas_field( 'hsq_heading' ),
		'alt'     => true,
	)
);
get_template_part( 'template-parts/stats' );
get_template_part( 'template-parts/testimonials', null, array( 'heading' => aerodallas_field( 'testimonials_heading' ) ) );
get_template_part( 'template-parts/insights', null, array( 'heading' => aerodallas_field( 'insights_heading' ) ) );
get_template_part( 'template-parts/faq', null, array( 'heading' => aerodallas_field( 'faq_heading' ) ) );
get_template_part( 'template-parts/cta-band' );

get_footer();
