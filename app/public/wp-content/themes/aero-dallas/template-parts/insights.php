<?php
/**
 * Latest insights: the three newest blog posts. Off until enabled in the
 * Customizer, and hidden if there are no posts.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

if ( ! get_theme_mod( 'aerodallas_show_insights', false ) ) {
	return;
}
$aerodallas_posts = get_posts(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
		'no_found_rows'  => true,
	)
);
if ( ! $aerodallas_posts ) {
	return;
}
?>
<section class="section" aria-labelledby="insights-title">
	<div class="container">
		<header class="section-head section-head--split" data-reveal>
			<div>
				<p class="eyebrow"><?php esc_html_e( 'News & Insights', 'aero-dallas' ); ?></p>
				<h2 class="section-title" id="insights-title"><?php echo aerodallas_highlight( $args['heading'] ?? __( 'Latest Insights', 'aero-dallas' ), __( 'Insights', 'aero-dallas' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			</div>
			<?php if ( aerodallas_news_page_id() ) : ?>
				<a class="link-arrow" href="<?php echo esc_url( aerodallas_news_url() ); ?>"><?php esc_html_e( 'View All Articles', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php endif; ?>
		</header>
		<ul class="news-grid">
			<?php foreach ( $aerodallas_posts as $aerodallas_i => $aerodallas_post ) : ?>
				<li data-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $aerodallas_i * 80 ) ); ?>ms">
					<?php get_template_part( 'template-parts/news-card', null, array( 'post' => $aerodallas_post ) ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
