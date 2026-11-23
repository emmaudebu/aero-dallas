<?php
/**
 * Individual service page: photo hero, "What's included" panel, detailed
 * write-up with a sticky quote sidebar, the process band, other services
 * and a quote band with this service pre-selected.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
the_post();

$aerodallas_service = get_post();
$aerodallas_all     = aerodallas_items( 'aerodallas_service' );
$aerodallas_ids     = wp_list_pluck( $aerodallas_all, 'ID' );
$aerodallas_index   = (int) array_search( $aerodallas_service->ID, $aerodallas_ids, true );
$aerodallas_anchor  = (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_anchor', true );
$aerodallas_icon    = (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_icon', true );
$aerodallas_scope   = aerodallas_service_scope( $aerodallas_service->ID );
$aerodallas_hero    = (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_hero_photo', true );
$aerodallas_hero    = $aerodallas_hero ? $aerodallas_hero : (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_photo', true );
$aerodallas_gallery = (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_gallery_photo', true );
$aerodallas_phone   = aerodallas_detail( 'phone' );
$aerodallas_others  = array_filter(
	$aerodallas_all,
	static function ( $s ) use ( $aerodallas_service ) {
		return $s->ID !== $aerodallas_service->ID;
	}
);
?>

<section class="service-hero" aria-labelledby="service-title">
	<div class="band__bg" aria-hidden="true">
		<?php echo aerodallas_image( (int) get_post_thumbnail_id(), $aerodallas_hero, array( 'sizes' => '100vw', 'eager' => true, 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php get_template_part( 'template-parts/flight-path' ); ?>
	<div class="container service-hero__inner">
		<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aero-dallas' ); ?>">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aero-dallas' ); ?></a></li>
				<li><a href="<?php echo esc_url( aerodallas_page_url( 'services' ) ); ?>"><?php esc_html_e( 'Services', 'aero-dallas' ); ?></a></li>
				<li aria-current="page"><?php the_title(); ?></li>
			</ol>
		</nav>
		<p class="service-hero__eyebrow">
			<span class="service-hero__letter" aria-hidden="true"><?php echo esc_html( aerodallas_letter( $aerodallas_index ) ); ?></span>
			<?php
			/* translators: %s: service letter, e.g. "A". */
			echo esc_html( sprintf( __( 'Service %s', 'aero-dallas' ), aerodallas_letter( $aerodallas_index ) ) );
			?>
		</p>
		<h1 class="service-hero__title" id="service-title"><?php the_title(); ?></h1>
		<p class="service-hero__lead"><?php echo esc_html( aerodallas_service_intro( $aerodallas_service ) ); ?></p>
		<div class="service-hero__actions">
			<a class="btn btn--primary btn--lg" href="<?php echo esc_url( aerodallas_quote_url( $aerodallas_anchor ) ); ?>"><?php esc_html_e( 'Request a Quote for This Service', 'aero-dallas' ); ?></a>
			<?php if ( $aerodallas_phone ) : ?>
				<a class="cta-band__phone" href="<?php echo esc_url( aerodallas_phone_href() ); ?>">
					<span class="cta-band__phone-icon"><?php echo aerodallas_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php echo esc_html( $aerodallas_phone ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( $aerodallas_scope ) : ?>
	<div class="scope-panel">
		<div class="container">
			<section class="scope-panel__card" aria-labelledby="scope-title">
				<div class="scope-panel__head">
					<?php if ( $aerodallas_icon ) : ?>
						<span class="icon-badge"><?php echo aerodallas_icon( $aerodallas_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
					<h2 class="scope-panel__title" id="scope-title"><?php esc_html_e( "What's Included", 'aero-dallas' ); ?></h2>
				</div>
				<ul class="scope-list">
					<?php foreach ( $aerodallas_scope as $aerodallas_item ) : ?>
						<li><?php echo aerodallas_icon( 'check-ring' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $aerodallas_item ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</section>
		</div>
	</div>
<?php endif; ?>

<div class="section service-detail">
	<div class="container service-detail__grid">
		<article class="service-detail__body prose prose--doc entry-content">
			<?php the_content(); ?>
		</article>

		<aside class="service-aside" aria-label="<?php esc_attr_e( 'Request a quote and other services', 'aero-dallas' ); ?>">
			<?php if ( $aerodallas_gallery ) : ?>
				<div class="service-aside__photo">
					<?php echo aerodallas_image( 0, $aerodallas_gallery, array( 'sizes' => '(min-width: 1024px) 360px, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
			<div class="service-aside__quote">
				<h2 class="service-aside__title"><?php esc_html_e( 'Need this service?', 'aero-dallas' ); ?></h2>
				<p><?php esc_html_e( 'Send your requirements and specifications. Our team will review them and guide you through the next steps.', 'aero-dallas' ); ?></p>
				<a class="btn btn--primary btn--block" href="<?php echo esc_url( aerodallas_quote_url( $aerodallas_anchor ) ); ?>"><?php esc_html_e( 'Request a Quote', 'aero-dallas' ); ?></a>
				<?php if ( $aerodallas_phone ) : ?>
					<a class="service-aside__phone" href="<?php echo esc_url( aerodallas_phone_href() ); ?>"><?php echo aerodallas_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $aerodallas_phone ); ?></a>
				<?php endif; ?>
			</div>
			<?php if ( $aerodallas_others ) : ?>
				<nav class="service-aside__nav" aria-labelledby="other-services-title">
					<h2 class="service-aside__label" id="other-services-title"><?php esc_html_e( 'Other services', 'aero-dallas' ); ?></h2>
					<ul>
						<?php foreach ( $aerodallas_others as $aerodallas_other ) : ?>
							<li>
								<a href="<?php echo esc_url( get_permalink( $aerodallas_other ) ); ?>">
									<span class="icon-badge icon-badge--sm"><?php echo aerodallas_icon( (string) get_post_meta( $aerodallas_other->ID, '_aerodallas_icon', true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									<?php echo esc_html( get_the_title( $aerodallas_other ) ); ?>
									<?php echo aerodallas_icon( 'arrow-right', 'service-aside__arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
		</aside>
	</div>
</div>

<?php
get_template_part( 'template-parts/equipment-gallery', null, array( 'service' => $aerodallas_anchor ) );

get_template_part(
	'template-parts/process-steps',
	null,
	array(
		'variant' => 'band',
		'heading' => __( 'How We Work', 'aero-dallas' ),
		'intro'   => __( 'AERO DALLAS INDUSTRIAL follows a structured approach to ensure that every client receives professional attention, clear communication, and appropriate technical support.', 'aero-dallas' ),
	)
);

if ( $aerodallas_others ) :
	?>
	<section class="section section--alt" aria-labelledby="more-services-title">
		<div class="container">
			<header class="section-head section-head--split" data-reveal>
				<div>
					<p class="eyebrow"><?php esc_html_e( 'More Services', 'aero-dallas' ); ?></p>
					<h2 class="section-title" id="more-services-title"><?php esc_html_e( 'Explore Our Other Services', 'aero-dallas' ); ?></h2>
				</div>
				<a class="link-arrow" href="<?php echo esc_url( aerodallas_page_url( 'services' ) ); ?>"><?php esc_html_e( 'View All Services', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</header>
			<ul class="service-grid service-grid--3">
				<?php
				foreach ( array_values( $aerodallas_others ) as $aerodallas_i => $aerodallas_other ) {
					get_template_part(
						'template-parts/service-card',
						null,
						array(
							'post'  => $aerodallas_other,
							'index' => (int) array_search( $aerodallas_other->ID, $aerodallas_ids, true ),
						)
					);
				}
				?>
			</ul>
		</div>
	</section>
	<?php
endif;

get_template_part( 'template-parts/cta-band', null, array( 'service' => $aerodallas_anchor ) );
get_footer();
