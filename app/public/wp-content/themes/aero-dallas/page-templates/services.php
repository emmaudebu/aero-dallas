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

get_template_part(
	'template-parts/page-header',
	null,
	array(
		'eyebrow' => aerodallas_field( 'eyebrow' ),
		'heading' => aerodallas_field( 'heading' ),
	)
);
?>

<div class="section section--tight">
	<div class="container services-intro">
		<div class="prose prose--lead entry-content" data-reveal>
			<?php the_content(); ?>
		</div>
		<?php if ( $aerodallas_services ) : ?>
			<nav class="jump-nav" aria-label="<?php esc_attr_e( 'Service groups', 'aero-dallas' ); ?>" data-reveal>
				<ol>
					<?php foreach ( $aerodallas_services as $aerodallas_i => $aerodallas_service ) : ?>
						<li><a href="#<?php echo esc_attr( (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_anchor', true ) ); ?>"><span aria-hidden="true"><?php echo esc_html( aerodallas_letter( $aerodallas_i ) ); ?></span> <?php echo esc_html( get_the_title( $aerodallas_service ) ); ?></a></li>
					<?php endforeach; ?>
				</ol>
			</nav>
		<?php endif; ?>
	</div>
</div>

<?php foreach ( $aerodallas_services as $aerodallas_i => $aerodallas_service ) : ?>
	<?php
	$aerodallas_anchor = (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_anchor', true );
	$aerodallas_icon   = (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_icon', true );
	$aerodallas_title  = get_the_title( $aerodallas_service );
	?>
	<section class="section service-block<?php echo 1 === $aerodallas_i % 2 ? ' service-block--alt section--alt' : ''; ?>" id="<?php echo esc_attr( $aerodallas_anchor ); ?>" aria-labelledby="<?php echo esc_attr( $aerodallas_anchor ); ?>-title">
		<div class="container split <?php echo 1 === $aerodallas_i % 2 ? 'split--media-left' : 'split--media-right'; ?>">
			<div class="split__body" data-reveal>
				<p class="service-block__letter"><span aria-hidden="true"><?php echo esc_html( aerodallas_letter( $aerodallas_i ) ); ?></span><?php echo $aerodallas_icon ? aerodallas_icon( $aerodallas_icon ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<h2 class="section-title" id="<?php echo esc_attr( $aerodallas_anchor ); ?>-title"><?php echo esc_html( $aerodallas_title ); ?></h2>
				<div class="prose service-block__content">
					<?php echo wp_kses_post( wpautop( $aerodallas_service->post_content ) ); ?>
				</div>
				<a class="btn btn--primary" href="<?php echo esc_url( aerodallas_quote_url( $aerodallas_anchor ) ); ?>">
					<?php esc_html_e( 'Request a quote for this service', 'aero-dallas' ); ?>
					<span class="screen-reader-text">: <?php echo esc_html( $aerodallas_title ); ?></span>
					<?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</div>
			<div class="split__media" data-reveal>
				<div class="framed">
					<?php echo aerodallas_image( (int) get_post_thumbnail_id( $aerodallas_service ), (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_photo', true ), array( 'sizes' => '(min-width: 1024px) 45vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
	</section>
<?php endforeach; ?>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
