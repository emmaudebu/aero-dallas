<?php
/**
 * Template Name: Policies
 *
 * Long-form layout with a sticky table of contents generated from the
 * H2 headings in the page content.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
the_post();

list( $aerodallas_content, $aerodallas_toc ) = aerodallas_policies_toc( apply_filters( 'the_content', get_the_content() ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.

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
	<div class="container policies">
		<?php if ( $aerodallas_toc ) : ?>
			<nav class="toc" aria-labelledby="toc-title">
				<h2 class="toc__title" id="toc-title"><?php esc_html_e( 'On this page', 'aero-dallas' ); ?></h2>
				<ol class="toc__list">
					<?php foreach ( $aerodallas_toc as $aerodallas_item ) : ?>
						<li><a href="#<?php echo esc_attr( $aerodallas_item['id'] ); ?>"><?php echo esc_html( $aerodallas_item['text'] ); ?></a></li>
					<?php endforeach; ?>
				</ol>
			</nav>
		<?php endif; ?>
		<article class="policies__body prose prose--doc entry-content">
			<?php echo $aerodallas_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output. ?>
		</article>
	</div>
</div>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
