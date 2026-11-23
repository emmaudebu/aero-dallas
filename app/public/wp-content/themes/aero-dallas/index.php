<?php
/**
 * News listing: the posts page, topic (category) and tag archives, and
 * article search. The newest article on the first page is shown as a wide
 * feature card, the rest in a grid.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;

$aerodallas_news_id    = aerodallas_news_page_id();
$aerodallas_news_label = $aerodallas_news_id ? get_the_title( $aerodallas_news_id ) : __( 'News', 'aero-dallas' );
$aerodallas_news_crumb = array(
	array(
		'label' => $aerodallas_news_label,
		'url'   => aerodallas_news_url(),
	),
);
$aerodallas_has_any    = (int) wp_count_posts()->publish > 0;
$aerodallas_posts      = $wp_query->posts;
$aerodallas_term       = ( is_category() || is_tag() ) ? get_queried_object() : null;

if ( is_search() ) {
	$aerodallas_query = get_search_query();
	$aerodallas_hero  = array(
		'title'     => __( 'Search', 'aero-dallas' ),
		'crumbs'    => $aerodallas_news_crumb,
		'eyebrow'   => __( 'Search Articles', 'aero-dallas' ),
		/* translators: %s: search query. */
		'heading'   => sprintf( __( 'Results for “%s”', 'aero-dallas' ), $aerodallas_query ),
		'highlight' => '“' . $aerodallas_query . '”',
		/* translators: %d: number of articles. */
		'lead'      => sprintf( _n( '%d article matches your search.', '%d articles match your search.', (int) $wp_query->found_posts, 'aero-dallas' ), (int) $wp_query->found_posts ),
		'photo'     => 'logistics-freighter',
		'thumb'     => 0,
	);
} elseif ( $aerodallas_term instanceof WP_Term ) {
	$aerodallas_desc = trim( wp_strip_all_tags( term_description( $aerodallas_term ) ) );
	$aerodallas_hero = array(
		'title'     => $aerodallas_term->name,
		'crumbs'    => $aerodallas_news_crumb,
		'eyebrow'   => is_category() ? __( 'Topic', 'aero-dallas' ) : __( 'Tag', 'aero-dallas' ),
		'heading'   => $aerodallas_term->name,
		'highlight' => $aerodallas_term->name,
		/* translators: %s: topic name. */
		'lead'      => '' !== $aerodallas_desc ? $aerodallas_desc : sprintf( __( 'Articles and updates from AERO DALLAS INDUSTRIAL about %s.', 'aero-dallas' ), $aerodallas_term->name ),
		'photo'     => $aerodallas_posts ? aerodallas_post_photo( $aerodallas_posts[0] ) : 'logistics-freighter',
		'thumb'     => 0,
	);
} elseif ( is_archive() ) {
	$aerodallas_title = wp_strip_all_tags( get_the_archive_title() );
	$aerodallas_hero  = array(
		'title'   => $aerodallas_title,
		'crumbs'  => $aerodallas_news_crumb,
		'eyebrow' => __( 'Archive', 'aero-dallas' ),
		'heading' => $aerodallas_title,
		'photo'   => 'logistics-freighter',
		'thumb'   => 0,
	);
} else {
	$aerodallas_hero = array(
		'title'     => $aerodallas_news_label,
		'eyebrow'   => $aerodallas_news_id ? aerodallas_field( 'eyebrow', $aerodallas_news_id ) : '',
		'heading'   => $aerodallas_news_id ? aerodallas_field( 'heading', $aerodallas_news_id ) : '',
		'highlight' => $aerodallas_news_id ? aerodallas_field( 'highlight', $aerodallas_news_id ) : '',
		'lead'      => $aerodallas_news_id ? aerodallas_field( 'lead', $aerodallas_news_id ) : '',
		'photo'     => 'logistics-freighter',
		'thumb'     => $aerodallas_news_id ? (int) get_post_thumbnail_id( $aerodallas_news_id ) : 0,
	);
}

$aerodallas_hero['actions'] = array(
	array(
		'label' => __( 'Request a Quote', 'aero-dallas' ),
		'url'   => aerodallas_quote_url(),
	),
	$aerodallas_posts
		? array(
			'label' => __( 'Read the Articles', 'aero-dallas' ),
			'url'   => '#articles',
			'style' => 'explore',
			'icon'  => 'arrow-down',
		)
		: array(
			'label' => __( 'Explore Our Services', 'aero-dallas' ),
			'url'   => aerodallas_page_url( 'services' ),
			'style' => 'explore',
		),
);

get_template_part( 'template-parts/page-hero', null, $aerodallas_hero );

if ( $aerodallas_has_any ) :
	$aerodallas_topics = aerodallas_news_filter_terms();
	?>
	<div class="news-filter">
		<div class="container">
			<div class="news-filter__inner">
				<nav class="news-filter__nav" aria-label="<?php esc_attr_e( 'Article topics', 'aero-dallas' ); ?>">
					<ul class="news-filter__topics">
						<li><a href="<?php echo esc_url( aerodallas_news_url() ); ?>"<?php echo is_home() ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'All Articles', 'aero-dallas' ); ?></a></li>
						<?php foreach ( $aerodallas_topics as $aerodallas_topic ) : ?>
							<li>
								<a href="<?php echo esc_url( get_category_link( $aerodallas_topic ) ); ?>"<?php echo is_category( $aerodallas_topic->term_id ) ? ' aria-current="page"' : ''; ?>>
									<?php echo esc_html( $aerodallas_topic->name ); ?>
									<span class="news-filter__count"><?php echo esc_html( number_format_i18n( $aerodallas_topic->count ) ); ?><span class="screen-reader-text"> <?php esc_html_e( 'articles', 'aero-dallas' ); ?></span></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
				<form class="news-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="screen-reader-text" for="news-search-input"><?php esc_html_e( 'Search articles', 'aero-dallas' ); ?></label>
					<input class="news-search__input" id="news-search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search articles…', 'aero-dallas' ); ?>">
					<input type="hidden" name="post_type" value="post">
					<button class="news-search__button" type="submit"><?php echo aerodallas_icon( 'magnifier' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="screen-reader-text"><?php esc_html_e( 'Search', 'aero-dallas' ); ?></span></button>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="section news-list<?php echo $aerodallas_has_any ? '' : ' news-list--overlap'; ?>" id="articles">
	<div class="container">
		<?php if ( $aerodallas_posts ) : ?>
			<?php
			$aerodallas_feature = ( is_home() && ! is_paged() ) ? array_shift( $aerodallas_posts ) : null;
			if ( $aerodallas_feature ) :
				?>
				<div class="news-feature" data-reveal>
					<?php
					get_template_part(
						'template-parts/news-card',
						null,
						array(
							'post'    => $aerodallas_feature,
							'variant' => 'feature',
							'level'   => 2,
						)
					);
					?>
				</div>
			<?php endif; ?>

			<?php if ( $aerodallas_posts ) : ?>
				<?php if ( $aerodallas_feature ) : ?>
					<header class="section-head section-head--split news-list__head" data-reveal>
						<div>
							<p class="eyebrow"><?php esc_html_e( 'Keep Reading', 'aero-dallas' ); ?></p>
							<h2 class="section-title"><?php echo aerodallas_highlight( __( 'More Articles', 'aero-dallas' ), __( 'Articles', 'aero-dallas' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
						</div>
					</header>
				<?php else : ?>
					<h2 class="screen-reader-text"><?php esc_html_e( 'Articles', 'aero-dallas' ); ?></h2>
				<?php endif; ?>
				<ul class="news-grid">
					<?php foreach ( $aerodallas_posts as $aerodallas_i => $aerodallas_post ) : ?>
						<li data-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( ( $aerodallas_i % 3 ) * 80 ) ); ?>ms">
							<?php get_template_part( 'template-parts/news-card', null, array( 'post' => $aerodallas_post ) ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php
			the_posts_pagination(
				array(
					'mid_size'           => 1,
					'prev_text'          => aerodallas_icon( 'arrow-left' ) . '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'aero-dallas' ) . '</span>',
					'next_text'          => aerodallas_icon( 'arrow-right' ) . '<span class="screen-reader-text">' . esc_html__( 'Next page', 'aero-dallas' ) . '</span>',
					'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'aero-dallas' ) . ' </span>',
					'screen_reader_text' => __( 'Article pages', 'aero-dallas' ),
					'class'              => 'news-pagination',
				)
			);
			?>
		<?php else : ?>
			<div class="news-empty" data-reveal>
				<span class="news-empty__icon"><?php echo aerodallas_icon( is_search() ? 'magnifier' : 'news' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php if ( is_search() ) : ?>
					<h2 class="news-empty__title"><?php esc_html_e( 'No Matching Articles', 'aero-dallas' ); ?></h2>
					<p><?php esc_html_e( 'Try different or fewer words, or browse all articles.', 'aero-dallas' ); ?></p>
				<?php elseif ( $aerodallas_has_any ) : ?>
					<h2 class="news-empty__title"><?php esc_html_e( 'No Articles Here Yet', 'aero-dallas' ); ?></h2>
					<p><?php esc_html_e( 'There are no articles in this section yet. Browse all articles instead.', 'aero-dallas' ); ?></p>
				<?php else : ?>
					<h2 class="news-empty__title"><?php esc_html_e( 'Articles Coming Soon', 'aero-dallas' ); ?></h2>
					<p><?php esc_html_e( 'Logistics updates, industry insights and company news will be published here. In the meantime, explore our services or get in touch with your requirements.', 'aero-dallas' ); ?></p>
				<?php endif; ?>
				<div class="news-empty__actions">
					<?php if ( $aerodallas_has_any ) : ?>
						<a class="btn btn--primary" href="<?php echo esc_url( aerodallas_news_url() ); ?>"><?php esc_html_e( 'Browse All Articles', 'aero-dallas' ); ?></a>
					<?php else : ?>
						<a class="btn btn--primary" href="<?php echo esc_url( aerodallas_page_url( 'services' ) ); ?>"><?php esc_html_e( 'Explore Our Services', 'aero-dallas' ); ?></a>
					<?php endif; ?>
					<a class="btn btn--outline" href="<?php echo esc_url( aerodallas_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Contact Us', 'aero-dallas' ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
