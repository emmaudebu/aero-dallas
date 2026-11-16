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
	)
);
if ( ! $aerodallas_posts ) {
	return;
}
$aerodallas_blog = (int) get_option( 'page_for_posts' );
?>
<section class="section" aria-labelledby="insights-title">
	<div class="container">
		<header class="section-head section-head--split" data-reveal>
			<div>
				<p class="eyebrow"><?php esc_html_e( 'Insights', 'aero-dallas' ); ?></p>
				<h2 class="section-title" id="insights-title"><?php echo esc_html( $args['heading'] ?? __( 'Latest Insights', 'aero-dallas' ) ); ?></h2>
			</div>
			<?php if ( $aerodallas_blog ) : ?>
				<a class="link-arrow" href="<?php echo esc_url( get_permalink( $aerodallas_blog ) ); ?>"><?php esc_html_e( 'View All', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php endif; ?>
		</header>
		<ul class="card-grid card-grid--3">
			<?php foreach ( $aerodallas_posts as $aerodallas_post ) : ?>
				<li class="post-card" data-reveal>
					<?php if ( has_post_thumbnail( $aerodallas_post ) ) : ?>
						<div class="post-card__media"><?php echo get_the_post_thumbnail( $aerodallas_post, 'aerodallas-card', array( 'alt' => '', 'sizes' => '(min-width: 1024px) 380px, 100vw' ) ); ?></div>
					<?php endif; ?>
					<div class="post-card__body">
						<h3 class="post-card__title"><a href="<?php echo esc_url( get_permalink( $aerodallas_post ) ); ?>"><?php echo esc_html( get_the_title( $aerodallas_post ) ); ?></a></h3>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $aerodallas_post ), 20 ) ); ?></p>
						<span class="link-arrow" aria-hidden="true"><?php esc_html_e( 'Read More', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
