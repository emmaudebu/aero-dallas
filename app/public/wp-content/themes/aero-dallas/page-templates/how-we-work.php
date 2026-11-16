<?php
/**
 * Template Name: How We Work
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
the_post();

get_template_part(
	'template-parts/page-header',
	null,
	array(
		'eyebrow' => aerodallas_field( 'eyebrow' ),
		'heading' => aerodallas_field( 'heading' ),
	)
);
?>

<div class="section">
	<div class="container process-layout">
		<div class="process-layout__intro">
			<div class="prose prose--lead entry-content" data-reveal>
				<?php the_content(); ?>
			</div>
			<?php if ( aerodallas_field( 'commitment_text' ) ) : ?>
				<aside class="commitment" aria-labelledby="commitment-title" data-reveal>
					<?php echo aerodallas_icon( 'shield', 'commitment__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<h2 class="commitment__title" id="commitment-title"><?php echo esc_html( aerodallas_field( 'commitment_heading' ) ); ?></h2>
					<p><?php echo esc_html( aerodallas_field( 'commitment_text' ) ); ?></p>
				</aside>
			<?php endif; ?>
		</div>
		<div class="process-layout__steps">
			<?php get_template_part( 'template-parts/process-steps', null, array( 'variant' => 'timeline' ) ); ?>
		</div>
	</div>
</div>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
