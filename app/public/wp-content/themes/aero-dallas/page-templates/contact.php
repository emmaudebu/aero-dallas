<?php
/**
 * Template Name: Contact Us
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
	<div class="container contact-layout">
		<div class="contact-layout__info">
			<div class="prose prose--lead entry-content">
				<?php the_content(); ?>
			</div>
			<?php get_template_part( 'template-parts/contact-details' ); ?>
		</div>
		<section class="quote-panel" id="quote" aria-labelledby="quote-title" tabindex="-1">
			<h2 class="quote-panel__title" id="quote-title"><?php echo esc_html( aerodallas_field( 'form_heading' ) ); ?></h2>
			<p class="quote-panel__note"><?php esc_html_e( 'Fields marked * are required.', 'aero-dallas' ); ?></p>
			<?php get_template_part( 'template-parts/quote-form' ); ?>
		</section>
	</div>
</div>

<?php
get_template_part( 'template-parts/faq', null, array( 'heading' => aerodallas_field( 'faq_heading' ) ) );
get_footer();
