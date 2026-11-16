<?php
/**
 * Default page template.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
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
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'container container--narrow prose prose--doc entry-content' ); ?>>
			<?php
			the_content();
			wp_link_pages(
				array(
					'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'aero-dallas' ) . '">',
					'after'  => '</nav>',
				)
			);
			?>
		</article>
	</div>
	<?php
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
