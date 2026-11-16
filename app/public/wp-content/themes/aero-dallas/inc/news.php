<?php
/**
 * News (blog) helpers: posts page, categories, reading time, fallback
 * photos, share links and related articles.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Categories created for new articles. Empty ones are never shown.
 *
 * @return array<string,string> slug => name
 */
function aerodallas_news_topics() {
	return array(
		'logistics'       => __( 'Logistics', 'aero-dallas' ),
		'aviation'        => __( 'Aviation Ground Support', 'aero-dallas' ),
		'industrial'      => __( 'Industrial Support', 'aero-dallas' ),
		'parts-equipment' => __( 'Parts & Equipment', 'aero-dallas' ),
		'company-news'    => __( 'Company News', 'aero-dallas' ),
	);
}

/**
 * ID of the page that lists the articles.
 *
 * @return int 0 when the site shows no posts page.
 */
function aerodallas_news_page_id() {
	$id = (int) get_option( 'page_for_posts' );
	return $id ? $id : aerodallas_page_id( 'news' );
}

/**
 * URL of the news listing.
 *
 * @return string
 */
function aerodallas_news_url() {
	$id = aerodallas_news_page_id();
	return $id ? get_permalink( $id ) : home_url( '/' );
}

/**
 * Main category of a post, ignoring an empty default category.
 *
 * @param WP_Post|int $post Post.
 * @return WP_Term|null
 */
function aerodallas_post_topic( $post ) {
	$terms = get_the_category( is_object( $post ) ? $post->ID : $post );
	if ( ! $terms ) {
		return null;
	}
	foreach ( $terms as $term ) {
		if ( 'uncategorized' !== $term->slug ) {
			return $term;
		}
	}
	return null;
}

/**
 * Bundled photo used when an article has no featured image, chosen by topic
 * and varied by post so neighbouring cards differ.
 *
 * @param WP_Post|int $post Post.
 * @return string Photo key.
 */
function aerodallas_post_photo( $post ) {
	$post  = get_post( $post );
	$topic = $post ? aerodallas_post_topic( $post ) : null;
	$slug  = $topic ? $topic->slug : '';
	$sets  = array(
		'logistic'  => array( 'logistics-freighter', 'service-logistics', 'band-truck-dusk' ),
		'freight'   => array( 'logistics-freighter', 'service-logistics', 'band-truck-dusk' ),
		'cargo'     => array( 'service-logistics', 'logistics-freighter', 'band-truck-dusk' ),
		'aviation'  => array( 'service-aviation', 'hero-ground-power', 'hero-jet' ),
		'aircraft'  => array( 'service-aviation', 'hero-ground-power', 'hero-jet' ),
		'industr'   => array( 'service-industrial', 'about-welding', 'detail-engine' ),
		'machin'    => array( 'service-industrial', 'about-welding', 'detail-engine' ),
		'part'      => array( 'service-parts', 'parts-detail' ),
		'equipment' => array( 'parts-detail', 'service-parts' ),
	);
	$photos = array( 'band-night-gate', 'service-logistics', 'hero-jet', 'detail-engine' );
	foreach ( $sets as $needle => $set ) {
		if ( false !== strpos( $slug, $needle ) ) {
			$photos = $set;
			break;
		}
	}
	return $photos[ ( $post ? (int) $post->ID : 0 ) % count( $photos ) ];
}

/**
 * Article image: featured image, or a bundled photo matching its topic.
 *
 * @param WP_Post $post Post.
 * @param array   $args aerodallas_image() args.
 * @return string
 */
function aerodallas_post_image( $post, $args = array() ) {
	return aerodallas_image( (int) get_post_thumbnail_id( $post ), aerodallas_post_photo( $post ), $args );
}

/**
 * Estimated reading time in minutes.
 *
 * @param WP_Post $post Post.
 * @return int
 */
function aerodallas_reading_time( $post ) {
	$words = str_word_count( wp_strip_all_tags( strip_shortcodes( (string) $post->post_content ) ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Short summary for cards: the excerpt, or trimmed content.
 *
 * @param WP_Post $post  Post.
 * @param int     $words Word limit.
 * @return string Plain text.
 */
function aerodallas_post_summary( $post, $words = 24 ) {
	$text = has_excerpt( $post ) ? $post->post_excerpt : strip_shortcodes( $post->post_content );
	return wp_trim_words( wp_strip_all_tags( excerpt_remove_blocks( $text ) ), $words, '…' );
}

/**
 * Categories that have published articles, for the topic filter.
 *
 * @return WP_Term[]
 */
function aerodallas_news_filter_terms() {
	$terms = get_categories(
		array(
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);
	return array_values(
		array_filter(
			$terms,
			static function ( $term ) {
				return 'uncategorized' !== $term->slug;
			}
		)
	);
}

/**
 * Up to $count other articles, preferring the same topic.
 *
 * @param WP_Post $post  Current post.
 * @param int     $count Number of posts.
 * @return WP_Post[]
 */
function aerodallas_related_posts( $post, $count = 3 ) {
	$base  = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $count,
		'post__not_in'        => array( $post->ID ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);
	$topic = aerodallas_post_topic( $post );
	$found = $topic ? get_posts( array_merge( $base, array( 'cat' => $topic->term_id ) ) ) : array();
	if ( count( $found ) < $count ) {
		$more  = get_posts(
			array_merge(
				$base,
				array(
					'posts_per_page' => $count - count( $found ),
					'post__not_in'   => array_merge( array( $post->ID ), wp_list_pluck( $found, 'ID' ) ),
				)
			)
		);
		$found = array_merge( $found, $more );
	}
	return $found;
}

/**
 * Share links for an article. No third-party scripts: plain share URLs.
 *
 * @param WP_Post $post Post.
 * @return array<int,array{label:string,icon:string,url:string}>
 */
function aerodallas_share_links( $post ) {
	$url   = rawurlencode( get_permalink( $post ) );
	$title = rawurlencode( wp_strip_all_tags( get_the_title( $post ) ) );
	return array(
		array(
			'label' => __( 'LinkedIn', 'aero-dallas' ),
			'icon'  => 'linkedin',
			'url'   => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
		),
		array(
			'label' => __( 'Facebook', 'aero-dallas' ),
			'icon'  => 'facebook',
			'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
		),
		array(
			'label' => __( 'X', 'aero-dallas' ),
			'icon'  => 'x-social',
			'url'   => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
		),
		array(
			'label' => __( 'Email', 'aero-dallas' ),
			'icon'  => 'mail',
			'url'   => 'mailto:?subject=' . $title . '&body=' . $url,
		),
	);
}

/**
 * Articles are company news, not a discussion forum: no comments or pings,
 * which also keeps comment spam away.
 *
 * @param bool $open    Whether open.
 * @param int  $post_id Post ID.
 * @return bool
 */
function aerodallas_close_post_comments( $open, $post_id ) {
	return 'post' === get_post_type( $post_id ) ? false : $open;
}
add_filter( 'comments_open', 'aerodallas_close_post_comments', 10, 2 );
add_filter( 'pings_open', 'aerodallas_close_post_comments', 10, 2 );

/**
 * Create the News page, make it the posts page, add it to the theme menus
 * before Contact Us, create the topic categories and rename the untouched
 * default category. Safe to run more than once.
 */
function aerodallas_setup_news() {
	$ids = aerodallas_setup_pages( false );
	$id  = $ids['news'] ?? 0;
	if ( ! $id ) {
		return;
	}
	if ( ! (int) get_option( 'page_for_posts' ) ) {
		update_option( 'page_for_posts', $id );
	}

	foreach ( aerodallas_news_topics() as $slug => $name ) {
		if ( ! term_exists( $slug, 'category' ) ) {
			wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
		}
	}
	$default = get_term( (int) get_option( 'default_category' ), 'category' );
	if ( $default instanceof WP_Term && 'uncategorized' === $default->slug && 'Uncategorized' === $default->name ) {
		wp_update_term(
			$default->term_id,
			'category',
			array(
				'name' => __( 'General', 'aero-dallas' ),
				'slug' => 'general',
			)
		);
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	foreach ( array_unique( array_filter( array_map( 'intval', (array) $locations ) ) ) as $menu_id ) {
		$items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
		if ( ! $items ) {
			continue;
		}
		$position = 0;
		foreach ( $items as $item ) {
			if ( 'post_type' === $item->type && (int) $item->object_id === $id ) {
				continue 2;
			}
			if ( ! $position && 'post_type' === $item->type && (int) $item->object_id === (int) ( $ids['contact'] ?? 0 ) ) {
				$position = (int) $item->menu_order;
			}
		}
		if ( $position ) {
			foreach ( $items as $item ) {
				if ( (int) $item->menu_order >= $position ) {
					wp_update_post(
						array(
							'ID'         => $item->ID,
							'menu_order' => (int) $item->menu_order + 1,
						)
					);
				}
			}
		}
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-object-id' => $id,
				'menu-item-object'    => 'page',
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $position ? $position : count( $items ) + 1,
			)
		);
	}
}
