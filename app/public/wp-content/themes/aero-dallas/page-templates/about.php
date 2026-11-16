<?php
/**
 * Template Name: About Us
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
the_post();

$aerodallas_values = aerodallas_items( 'aerodallas_value' );

get_template_part(
	'template-parts/page-header',
	null,
	array(
		'eyebrow' => aerodallas_field( 'eyebrow' ),
		'heading' => aerodallas_field( 'heading' ),
	)
);
?>

<section class="section" aria-label="<?php esc_attr_e( 'Who we are', 'aero-dallas' ); ?>">
	<div class="container split split--media-right">
		<div class="split__body prose prose--lead-first entry-content" data-reveal>
			<?php the_content(); ?>
		</div>
		<div class="split__media" data-reveal>
			<div class="framed framed--offset">
				<?php echo aerodallas_image( (int) get_theme_mod( 'aerodallas_about_image', 0 ), 'about-welding', array( 'sizes' => '(min-width: 1024px) 42vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
</section>

<?php if ( aerodallas_field( 'vision' ) || aerodallas_field( 'mission' ) ) : ?>
<section class="section section--navy vm" aria-label="<?php esc_attr_e( 'Vision and mission', 'aero-dallas' ); ?>">
	<div class="vm__media" aria-hidden="true">
		<?php echo aerodallas_image( (int) get_theme_mod( 'aerodallas_detail_image', 0 ), 'detail-engine', array( 'sizes' => '50vw', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<div class="container vm__grid">
		<?php if ( aerodallas_field( 'vision' ) ) : ?>
			<div class="vm__card" data-reveal>
				<h2 class="eyebrow eyebrow--light"><?php esc_html_e( 'Vision', 'aero-dallas' ); ?></h2>
				<p class="vm__text"><?php echo esc_html( aerodallas_field( 'vision' ) ); ?></p>
			</div>
		<?php endif; ?>
		<?php if ( aerodallas_field( 'mission' ) ) : ?>
			<div class="vm__card" data-reveal style="--reveal-delay: 100ms">
				<h2 class="eyebrow eyebrow--light"><?php esc_html_e( 'Mission', 'aero-dallas' ); ?></h2>
				<p class="vm__text"><?php echo esc_html( aerodallas_field( 'mission' ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php if ( $aerodallas_values ) : ?>
<section class="section" aria-labelledby="values-title">
	<div class="container">
		<header class="section-head" data-reveal>
			<p class="section-index"><?php esc_html_e( 'What guides our work', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="values-title"><?php echo esc_html( aerodallas_field( 'values_heading' ) ); ?></h2>
		</header>
		<ul class="values">
			<?php foreach ( $aerodallas_values as $aerodallas_i => $aerodallas_value ) : ?>
				<?php $aerodallas_icon = (string) get_post_meta( $aerodallas_value->ID, '_aerodallas_icon', true ); ?>
				<li class="values__item" data-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $aerodallas_i * 60 ) ); ?>ms">
					<span class="values__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $aerodallas_i + 1 ) ); ?></span>
					<?php if ( $aerodallas_icon ) : ?>
						<span class="values__icon"><?php echo aerodallas_icon( $aerodallas_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
					<h3 class="values__title"><?php echo esc_html( get_the_title( $aerodallas_value ) ); ?></h3>
					<?php if ( has_excerpt( $aerodallas_value ) ) : ?>
						<p><?php echo esc_html( get_the_excerpt( $aerodallas_value ) ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/why-choose', null, array( 'heading' => aerodallas_field( 'why_heading' ) ) );
get_template_part( 'template-parts/cta-band' );
get_footer();
