<?php
/**
 * Fallback template: blog index and archives.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_home() && ! is_front_page() ) {
	$aerodallas_title = single_post_title( '', false );
} elseif ( is_search() ) {
	/* translators: %s: search query. */
	$aerodallas_title = sprintf( __( 'Search results for “%s”', 'aero-dallas' ), get_search_query() );
} elseif ( is_archive() ) {
	$aerodallas_title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$aerodallas_title = __( 'News', 'aero-dallas' );
}
?>
<header class="page-header">
	<div class="page-header__grid" aria-hidden="true"></div>
	<div class="container page-header__inner">
		<h1 class="page-header__title"><?php echo esc_html( $aerodallas_title ); ?></h1>
	</div>
</header>

<div class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<ul class="post-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li <?php post_class( 'post-list__item' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="post-list__media"><?php the_post_thumbnail( 'aerodallas-card', array( 'sizes' => '(min-width: 1024px) 30vw, 100vw' ) ); ?></div>
						<?php endif; ?>
						<p class="post-list__date"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
						<h2 class="post-list__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php
			the_posts_pagination(
				array(
					'prev_text' => __( 'Previous', 'aero-dallas' ),
					'next_text' => __( 'Next', 'aero-dallas' ),
				)
			);
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing has been published here yet.', 'aero-dallas' ); ?></p>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
