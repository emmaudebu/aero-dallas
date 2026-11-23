<?php
/**
 * Template Name: Services
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
the_post();

$aerodallas_services = aerodallas_items( 'aerodallas_service' );

ob_start();
the_content();
$aerodallas_intro = (string) ob_get_clean();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'eyebrow'   => aerodallas_field( 'eyebrow' ),
		'heading'   => aerodallas_field( 'heading' ),
		'highlight' => aerodallas_field( 'highlight' ),
		'lead_html' => $aerodallas_intro,
		'photo'     => 'service-aviation',
		'actions'   => array(
			array(
				'label' => __( 'Get a Quote', 'aero-dallas' ),
				'url'   => aerodallas_quote_url(),
			),
			array(
				'label' => __( 'How We Work', 'aero-dallas' ),
				'url'   => aerodallas_page_url( 'process' ),
				'style' => 'explore',
			),
		),
	)
);

get_template_part(
	'template-parts/hero-panel',
	null,
	array(
		'label' => __( 'Jump to a service', 'aero-dallas' ),
		'items' => aerodallas_service_panel_items( true ),
	)
);
?>

<div class="section services-list">
	<div class="container">
		<header class="section-head section-head--center" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'What We Do', 'aero-dallas' ); ?></p>
			<h2 class="section-title"><?php echo aerodallas_highlight( __( 'Four Areas of Specialised Support', 'aero-dallas' ), __( 'Specialised Support', 'aero-dallas' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		</header>

		<?php foreach ( $aerodallas_services as $aerodallas_i => $aerodallas_service ) : ?>
			<?php
			$aerodallas_anchor = (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_anchor', true );
			$aerodallas_icon   = (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_icon', true );
			$aerodallas_title  = get_the_title( $aerodallas_service );
			$aerodallas_scope  = aerodallas_service_scope( $aerodallas_service->ID );
			$aerodallas_flip   = 1 === $aerodallas_i % 2;
			?>
			<section class="service-row<?php echo $aerodallas_flip ? ' service-row--flip' : ''; ?>" id="<?php echo esc_attr( $aerodallas_anchor ); ?>" aria-labelledby="<?php echo esc_attr( $aerodallas_anchor ); ?>-title" data-reveal>
				<div class="service-row__media">
					<a href="<?php echo esc_url( get_permalink( $aerodallas_service ) ); ?>" tabindex="-1" aria-hidden="true">
						<?php echo aerodallas_image( (int) get_post_thumbnail_id( $aerodallas_service ), (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_photo', true ), array( 'sizes' => '(min-width: 1024px) 560px, 100vw', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
					<span class="service-row__letter" aria-hidden="true"><?php echo esc_html( aerodallas_letter( $aerodallas_i ) ); ?></span>
				</div>
				<div class="service-row__body">
					<p class="service-row__kicker">
						<?php if ( $aerodallas_icon ) : ?>
							<span class="icon-badge icon-badge--sm"><?php echo aerodallas_icon( $aerodallas_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php endif; ?>
						<?php
						/* translators: %s: service letter. */
						echo esc_html( sprintf( __( 'Service %s', 'aero-dallas' ), aerodallas_letter( $aerodallas_i ) ) );
						?>
					</p>
					<h2 class="service-row__title" id="<?php echo esc_attr( $aerodallas_anchor ); ?>-title"><a href="<?php echo esc_url( get_permalink( $aerodallas_service ) ); ?>"><?php echo esc_html( $aerodallas_title ); ?></a></h2>
					<p class="service-row__intro"><?php echo esc_html( aerodallas_service_intro( $aerodallas_service ) ); ?></p>
					<?php if ( $aerodallas_scope ) : ?>
						<ul class="scope-list scope-list--compact">
							<?php foreach ( $aerodallas_scope as $aerodallas_item ) : ?>
								<li><?php echo aerodallas_icon( 'check-ring' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $aerodallas_item ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<div class="service-block__actions">
						<a class="btn btn--primary" href="<?php echo esc_url( get_permalink( $aerodallas_service ) ); ?>">
							<?php esc_html_e( 'View Service Details', 'aero-dallas' ); ?>
							<span class="screen-reader-text">: <?php echo esc_html( $aerodallas_title ); ?></span>
							<?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
						<a class="btn btn--outline" href="<?php echo esc_url( aerodallas_quote_url( $aerodallas_anchor ) ); ?>">
							<?php esc_html_e( 'Request a Quote', 'aero-dallas' ); ?>
							<span class="screen-reader-text">: <?php echo esc_html( $aerodallas_title ); ?></span>
						</a>
					</div>
				</div>
			</section>
		<?php endforeach; ?>
	</div>
</div>

<?php
get_template_part(
	'template-parts/process-steps',
	null,
	array(
		'variant' => 'band',
		'heading' => __( 'How We Work', 'aero-dallas' ),
		'intro'   => __( 'AERO DALLAS INDUSTRIAL follows a structured approach to ensure that every client receives professional attention, clear communication, and appropriate technical support.', 'aero-dallas' ),
	)
);
get_template_part( 'template-parts/cta-band' );
get_footer();
