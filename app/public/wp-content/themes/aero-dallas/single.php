<?php
/**
 * Single post.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<header class="page-header">
		<div class="page-header__grid" aria-hidden="true"></div>
		<div class="container page-header__inner">
			<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aero-dallas' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aero-dallas' ); ?></a></li>
					<li aria-current="page"><?php the_title(); ?></li>
				</ol>
			</nav>
			<h1 class="page-header__title"><?php the_title(); ?></h1>
			<p class="page-header__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
		</div>
	</header>
	<div class="section">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'container container--narrow prose prose--doc entry-content' ); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="post-hero"><?php the_post_thumbnail( 'aerodallas-wide', array( 'sizes' => '(min-width: 760px) 720px, 100vw' ) ); ?></figure>
			<?php endif; ?>
			<?php
			the_content();
			wp_link_pages();
			?>
		</article>
		<div class="container container--narrow">
			<?php
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-label">' . esc_html__( 'Previous', 'aero-dallas' ) . '</span> %title',
					'next_text' => '<span class="nav-label">' . esc_html__( 'Next', 'aero-dallas' ) . '</span> %title',
				)
			);
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</div>
	<?php
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
