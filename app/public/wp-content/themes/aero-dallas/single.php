<?php
/**
 * Single article: photo hero with topic, date and reading time; the article
 * beside a sticky quote card and topic list; share links, previous / next
 * articles and related articles.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
the_post();

$aerodallas_post    = get_post();
$aerodallas_topic   = aerodallas_post_topic( $aerodallas_post );
$aerodallas_minutes = aerodallas_reading_time( $aerodallas_post );
$aerodallas_news_id = aerodallas_news_page_id();
$aerodallas_crumbs  = array(
	array(
		'label' => $aerodallas_news_id ? get_the_title( $aerodallas_news_id ) : __( 'News', 'aero-dallas' ),
		'url'   => aerodallas_news_url(),
	),
);
if ( $aerodallas_topic ) {
	$aerodallas_crumbs[] = array(
		'label' => $aerodallas_topic->name,
		'url'   => get_category_link( $aerodallas_topic ),
	);
}
$aerodallas_meta = array(
	array(
		'icon' => 'calendar',
		'text' => get_the_date(),
	),
	array(
		'icon' => 'clock',
		/* translators: %d: minutes. */
		'text' => sprintf( _n( '%d min read', '%d min read', $aerodallas_minutes, 'aero-dallas' ), $aerodallas_minutes ),
	),
);
if ( get_the_modified_date( 'Y-m-d' ) > get_the_date( 'Y-m-d' ) ) {
	$aerodallas_meta[] = array(
		'icon' => 'check-ring',
		/* translators: %s: date. */
		'text' => sprintf( __( 'Updated %s', 'aero-dallas' ), get_the_modified_date() ),
	);
}

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'class'   => 'page-hero--article',
		'crumbs'  => $aerodallas_crumbs,
		'eyebrow' => $aerodallas_topic ? $aerodallas_topic->name : __( 'News', 'aero-dallas' ),
		'meta'    => $aerodallas_meta,
		'lead'    => has_excerpt() ? get_the_excerpt() : '',
		'photo'   => aerodallas_post_photo( $aerodallas_post ),
	)
);

// Pre-select the matching service on the quote form.
$aerodallas_service = '';
if ( $aerodallas_topic ) {
	foreach ( array(
		'logistic'  => 'logistics',
		'aviation'  => 'aviation',
		'industr'   => 'industrial',
		'part'      => 'parts-supply',
		'equipment' => 'parts-supply',
	) as $aerodallas_needle => $aerodallas_anchor ) {
		if ( false !== strpos( $aerodallas_topic->slug, $aerodallas_needle ) ) {
			$aerodallas_service = $aerodallas_anchor;
			break;
		}
	}
}
$aerodallas_phone  = aerodallas_detail( 'phone' );
$aerodallas_topics = aerodallas_news_filter_terms();
$aerodallas_prev   = get_previous_post();
$aerodallas_next   = get_next_post();
?>

<div class="section article-wrap">
	<div class="container article-layout">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>
			<div class="prose prose--article entry-content">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Article pages', 'aero-dallas' ) . '">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>

			<footer class="article-footer">
				<?php if ( has_tag() ) : ?>
					<div class="article-tags">
						<span class="article-tags__label"><?php echo aerodallas_icon( 'tag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Tags', 'aero-dallas' ); ?></span>
						<?php the_tags( '<ul class="article-tags__list"><li>', '</li><li>', '</li></ul>' ); ?>
					</div>
				<?php endif; ?>
				<div class="article-share">
					<h2 class="article-share__title"><?php esc_html_e( 'Share this article', 'aero-dallas' ); ?></h2>
					<ul class="article-share__list">
						<?php foreach ( aerodallas_share_links( $aerodallas_post ) as $aerodallas_share ) : ?>
							<li>
								<a class="article-share__link" href="<?php echo esc_url( $aerodallas_share['url'] ); ?>"<?php echo 0 === strpos( $aerodallas_share['url'], 'mailto:' ) ? '' : ' target="_blank" rel="noopener"'; ?>>
									<?php echo aerodallas_icon( $aerodallas_share['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<span class="screen-reader-text">
										<?php
										/* translators: %s: network name. */
										echo esc_html( sprintf( __( 'Share on %s', 'aero-dallas' ), $aerodallas_share['label'] ) );
										echo 0 === strpos( $aerodallas_share['url'], 'mailto:' ) ? '' : ' ' . esc_html__( '(opens in a new tab)', 'aero-dallas' );
										?>
									</span>
								</a>
							</li>
						<?php endforeach; ?>
						<li>
							<button class="article-share__link" type="button" data-copy-link="<?php echo esc_url( get_permalink() ); ?>" data-copied="<?php esc_attr_e( 'Link copied', 'aero-dallas' ); ?>" hidden>
								<?php echo aerodallas_icon( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span class="screen-reader-text"><?php esc_html_e( 'Copy link', 'aero-dallas' ); ?></span>
							</button>
						</li>
					</ul>
					<p class="article-share__status" role="status"></p>
				</div>
			</footer>
		</article>

		<aside class="service-aside article-aside" aria-label="<?php esc_attr_e( 'Request a quote and browse topics', 'aero-dallas' ); ?>">
			<div class="service-aside__quote">
				<span class="icon-badge article-aside__icon"><?php echo aerodallas_icon( 'headset' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<h2 class="service-aside__title"><?php esc_html_e( 'Need Support With Your Operations?', 'aero-dallas' ); ?></h2>
				<p><?php esc_html_e( 'Send your requirements and specifications. Our team will review them and guide you through the next steps.', 'aero-dallas' ); ?></p>
				<a class="btn btn--primary btn--block" href="<?php echo esc_url( aerodallas_quote_url( $aerodallas_service ) ); ?>"><?php esc_html_e( 'Request a Quote', 'aero-dallas' ); ?></a>
				<?php if ( $aerodallas_phone ) : ?>
					<a class="service-aside__phone" href="<?php echo esc_url( aerodallas_phone_href() ); ?>"><?php echo aerodallas_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $aerodallas_phone ); ?></a>
				<?php endif; ?>
			</div>
			<?php if ( $aerodallas_topics ) : ?>
				<nav class="service-aside__nav" aria-labelledby="article-topics-title">
					<h2 class="service-aside__label" id="article-topics-title"><?php esc_html_e( 'Browse topics', 'aero-dallas' ); ?></h2>
					<ul>
						<?php foreach ( $aerodallas_topics as $aerodallas_term ) : ?>
							<li>
								<a href="<?php echo esc_url( get_category_link( $aerodallas_term ) ); ?>"<?php echo ( $aerodallas_topic && $aerodallas_topic->term_id === $aerodallas_term->term_id ) ? ' class="is-current"' : ''; ?>>
									<?php echo esc_html( $aerodallas_term->name ); ?>
									<span class="article-aside__count"><?php echo esc_html( number_format_i18n( $aerodallas_term->count ) ); ?><span class="screen-reader-text"> <?php esc_html_e( 'articles', 'aero-dallas' ); ?></span></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
		</aside>
	</div>

	<?php if ( $aerodallas_prev || $aerodallas_next ) : ?>
		<nav class="container article-nav" aria-label="<?php esc_attr_e( 'Previous and next articles', 'aero-dallas' ); ?>">
			<?php if ( $aerodallas_prev ) : ?>
				<a class="article-nav__link article-nav__link--prev" href="<?php echo esc_url( get_permalink( $aerodallas_prev ) ); ?>">
					<span class="article-nav__label"><?php echo aerodallas_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Previous Article', 'aero-dallas' ); ?></span>
					<span class="article-nav__title"><?php echo esc_html( get_the_title( $aerodallas_prev ) ); ?></span>
				</a>
			<?php endif; ?>
			<?php if ( $aerodallas_next ) : ?>
				<a class="article-nav__link article-nav__link--next" href="<?php echo esc_url( get_permalink( $aerodallas_next ) ); ?>">
					<span class="article-nav__label"><?php esc_html_e( 'Next Article', 'aero-dallas' ); ?><?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="article-nav__title"><?php echo esc_html( get_the_title( $aerodallas_next ) ); ?></span>
				</a>
			<?php endif; ?>
		</nav>
	<?php endif; ?>
</div>

<?php
$aerodallas_related = aerodallas_related_posts( $aerodallas_post );
if ( $aerodallas_related ) :
	?>
	<section class="section section--alt" aria-labelledby="related-title">
		<div class="container">
			<header class="section-head section-head--split" data-reveal>
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Keep Reading', 'aero-dallas' ); ?></p>
					<h2 class="section-title" id="related-title"><?php echo aerodallas_highlight( __( 'Related Articles', 'aero-dallas' ), __( 'Articles', 'aero-dallas' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				</div>
				<a class="link-arrow" href="<?php echo esc_url( aerodallas_news_url() ); ?>"><?php esc_html_e( 'View All Articles', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</header>
			<ul class="news-grid">
				<?php foreach ( $aerodallas_related as $aerodallas_i => $aerodallas_item ) : ?>
					<li data-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $aerodallas_i * 80 ) ); ?>ms">
						<?php get_template_part( 'template-parts/news-card', null, array( 'post' => $aerodallas_item ) ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
endif;

get_template_part( 'template-parts/cta-band' );
get_footer();
