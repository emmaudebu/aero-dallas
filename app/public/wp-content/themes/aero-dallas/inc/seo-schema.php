<?php
/**
 * Meta description, Open Graph / Twitter tags and JSON-LD structured data.
 * Tags are skipped when a dedicated SEO plugin is active.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether a known SEO plugin is handling titles, descriptions and social tags.
 *
 * @return bool
 */
function aerodallas_seo_plugin_active() {
	$active = defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'AIOSEO_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| defined( 'THE_SEO_FRAMEWORK_VERSION' )
		|| defined( 'SLIM_SEO_VER' );
	/**
	 * Filter whether the theme should defer SEO tags to a plugin.
	 *
	 * @param bool $active Detected state.
	 */
	return (bool) apply_filters( 'aerodallas_seo_plugin_active', $active );
}

/**
 * Title separator.
 *
 * @return string
 */
function aerodallas_title_separator() {
	return '|';
}
add_filter( 'document_title_separator', 'aerodallas_title_separator' );

/**
 * Meta description for the current view.
 *
 * @return string
 */
function aerodallas_meta_description() {
	$desc = '';
	if ( is_singular() ) {
		$id   = get_queried_object_id();
		$desc = (string) get_post_meta( $id, '_aerodallas_seo_description', true );
		if ( '' === $desc && is_front_page() ) {
			$desc = aerodallas_hero( 'hero_lead' );
		}
		if ( '' === $desc && is_singular( 'aerodallas_service' ) ) {
			$desc = aerodallas_service_intro( get_post( $id ) );
		}
		if ( '' === $desc ) {
			$post = get_post( $id );
			$desc = has_excerpt( $post ) ? $post->post_excerpt : wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
		}
	} elseif ( is_home() && aerodallas_news_page_id() ) {
		$news = aerodallas_news_page_id();
		$desc = (string) get_post_meta( $news, '_aerodallas_seo_description', true );
		$desc = '' !== $desc ? $desc : aerodallas_field( 'lead', $news );
	} elseif ( is_category() || is_tag() ) {
		$desc = term_description();
		/* translators: %s: topic name. */
		$desc = '' !== trim( wp_strip_all_tags( $desc ) ) ? $desc : sprintf( __( 'Articles and updates from AERO DALLAS INDUSTRIAL about %s.', 'aero-dallas' ), single_term_title( '', false ) );
	} elseif ( is_home() || is_archive() ) {
		$desc = get_bloginfo( 'description' );
	}
	$desc = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $desc ) ) );
	return wp_html_excerpt( $desc, 160, mb_strlen( $desc ) > 160 ? '…' : '' );
}

/**
 * Print description, Open Graph and Twitter tags.
 */
function aerodallas_social_meta() {
	if ( aerodallas_seo_plugin_active() || is_404() ) {
		return;
	}
	$desc  = aerodallas_meta_description();
	$title = wp_get_document_title();
	$url   = is_singular() ? get_permalink( get_queried_object_id() ) : home_url( add_query_arg( array() ) );
	if ( is_home() && aerodallas_news_page_id() && ! is_paged() ) {
		$url = aerodallas_news_url();
	}
	if ( is_front_page() ) {
		$url = home_url( '/' );
	}
	$image = AERODALLAS_URI . '/assets/img/brand/og-image.png';
	if ( is_singular() && has_post_thumbnail( get_queried_object_id() ) ) {
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_queried_object_id() ), 'aerodallas-wide' );
		$image = $thumb ? $thumb[0] : $image;
	}

	if ( $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}
	$tags = array(
		'og:type'        => is_single() ? 'article' : 'website',
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:title'       => $title,
		'og:description' => $desc,
		'og:url'         => $url,
		'og:image'       => $image,
		'og:locale'      => str_replace( '-', '_', get_bloginfo( 'language' ) ),
	);
	foreach ( $tags as $property => $content ) {
		if ( '' !== (string) $content ) {
			printf( '<meta property="%1$s" content="%2$s">' . "\n", esc_attr( $property ), 'og:url' === $property || 'og:image' === $property ? esc_url( $content ) : esc_attr( $content ) );
		}
	}
	if ( false !== strpos( $image, 'og-image.png' ) ) {
		echo '<meta property="og:image:width" content="1200">' . "\n" . '<meta property="og:image:height" content="630">' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( $desc ) {
		printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) );
	}
	printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
}
add_action( 'wp_head', 'aerodallas_social_meta', 3 );

/**
 * Organization node with only the confirmed details that are filled in.
 *
 * @return array
 */
function aerodallas_schema_organization() {
	$org = array(
		'@type'  => 'Organization',
		'@id'    => home_url( '/#organization' ),
		'name'   => 'AERO DALLAS INDUSTRIAL',
		'url'    => home_url( '/' ),
		'logo'   => array(
			'@type'  => 'ImageObject',
			'url'    => AERODALLAS_URI . '/assets/img/brand/logo-main.png',
			'width'  => 553,
			'height' => 185,
		),
		'slogan' => get_bloginfo( 'description' ),
	);
	$phone = aerodallas_detail( 'phone' );
	if ( $phone ) {
		$org['telephone'] = preg_replace( '/[^\d+]/', '', $phone );
	}
	$email = sanitize_email( aerodallas_detail( 'email' ) );
	if ( $email ) {
		$org['email'] = $email;
	}
	$address = aerodallas_detail( 'address' );
	if ( $address ) {
		$org['address'] = array(
			'@type'         => 'PostalAddress',
			'streetAddress' => preg_replace( '/\s*\n\s*/', ', ', $address ),
		);
	}
	$regions = aerodallas_detail( 'regions' );
	if ( $regions ) {
		$org['areaServed'] = $regions;
	}
	$instagram = aerodallas_detail( 'instagram_url' );
	if ( $instagram ) {
		$org['sameAs'] = array( esc_url_raw( $instagram ) );
	}
	return array_filter( $org );
}

/**
 * FAQPage node from the FAQ items.
 *
 * @return array|null
 */
function aerodallas_schema_faq() {
	$faqs = aerodallas_items( 'aerodallas_faq' );
	if ( ! $faqs ) {
		return null;
	}
	$entities = array();
	foreach ( $faqs as $faq ) {
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( get_the_title( $faq ) ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => trim( wp_strip_all_tags( $faq->post_content ) ),
			),
		);
	}
	return array(
		'@type'      => 'FAQPage',
		'@id'        => get_permalink( get_queried_object_id() ) . '#faq',
		'mainEntity' => $entities,
	);
}

/**
 * Service nodes from the services.
 *
 * @return array[]
 */
function aerodallas_schema_services() {
	$nodes = array();
	foreach ( aerodallas_items( 'aerodallas_service' ) as $service ) {
		$nodes[] = array(
			'@type'       => 'Service',
			'name'        => wp_strip_all_tags( get_the_title( $service ) ),
			'serviceType' => wp_strip_all_tags( get_the_title( $service ) ),
			'description' => wp_strip_all_tags( aerodallas_service_intro( $service ) ),
			'url'         => get_permalink( $service ),
			'provider'    => array( '@id' => home_url( '/#organization' ) ),
		);
	}
	return $nodes;
}

/**
 * BlogPosting node for an article.
 *
 * @param WP_Post $post Post.
 * @return array
 */
function aerodallas_schema_article( $post ) {
	$image = AERODALLAS_URI . '/assets/img/brand/og-image.png';
	if ( has_post_thumbnail( $post ) ) {
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'aerodallas-wide' );
		$image = $thumb ? $thumb[0] : $image;
	}
	$topic = aerodallas_post_topic( $post );
	$node  = array(
		'@type'            => 'BlogPosting',
		'@id'              => get_permalink( $post ) . '#article',
		'headline'         => wp_strip_all_tags( get_the_title( $post ) ),
		'description'      => aerodallas_post_summary( $post, 40 ),
		'datePublished'    => get_the_date( 'c', $post ),
		'dateModified'     => get_the_modified_date( 'c', $post ),
		'image'            => $image,
		'url'              => get_permalink( $post ),
		'mainEntityOfPage' => get_permalink( $post ),
		'wordCount'        => str_word_count( wp_strip_all_tags( $post->post_content ) ),
		'author'           => array( '@id' => home_url( '/#organization' ) ),
		'publisher'        => array( '@id' => home_url( '/#organization' ) ),
	);
	if ( $topic ) {
		$node['articleSection'] = $topic->name;
	}
	return $node;
}

/**
 * Print the JSON-LD graph.
 */
function aerodallas_schema() {
	if ( is_404() ) {
		return;
	}
	$graph   = array();
	$plugin  = aerodallas_seo_plugin_active();
	$page_id = is_singular() ? get_queried_object_id() : 0;

	if ( ! $plugin ) {
		$graph[] = aerodallas_schema_organization();
		$graph[] = array(
			'@type'     => 'WebSite',
			'@id'       => home_url( '/#website' ),
			'url'       => home_url( '/' ),
			'name'      => get_bloginfo( 'name' ),
			'publisher' => array( '@id' => home_url( '/#organization' ) ),
		);
		if ( $page_id && ! is_front_page() ) {
			$crumbs = array(
				array(
					'name' => __( 'Home', 'aero-dallas' ),
					'item' => home_url( '/' ),
				),
			);
			if ( is_singular( 'aerodallas_service' ) && aerodallas_page_id( 'services' ) ) {
				$crumbs[] = array(
					'name' => wp_strip_all_tags( get_the_title( aerodallas_page_id( 'services' ) ) ),
					'item' => aerodallas_page_url( 'services' ),
				);
			}
			if ( is_singular( 'post' ) && aerodallas_news_page_id() ) {
				$crumbs[] = array(
					'name' => wp_strip_all_tags( get_the_title( aerodallas_news_page_id() ) ),
					'item' => aerodallas_news_url(),
				);
			}
			$crumbs[] = array(
				'name' => wp_strip_all_tags( get_the_title( $page_id ) ),
				'item' => get_permalink( $page_id ),
			);
			$items = array();
			foreach ( $crumbs as $position => $crumb ) {
				$items[] = array_merge(
					array(
						'@type'    => 'ListItem',
						'position' => $position + 1,
					),
					$crumb
				);
			}
			$graph[] = array(
				'@type'           => 'BreadcrumbList',
				'@id'             => get_permalink( $page_id ) . '#breadcrumb',
				'itemListElement' => $items,
			);
		}
	}

	if ( is_page_template( 'page-templates/services.php' ) ) {
		$graph = array_merge( $graph, aerodallas_schema_services() );
	}
	if ( is_singular( 'aerodallas_service' ) ) {
		$service = get_queried_object();
		$graph[] = array(
			'@type'       => 'Service',
			'@id'         => get_permalink( $service ) . '#service',
			'name'        => wp_strip_all_tags( get_the_title( $service ) ),
			'serviceType' => wp_strip_all_tags( get_the_title( $service ) ),
			'description' => wp_strip_all_tags( aerodallas_service_intro( $service ) ),
			'url'         => get_permalink( $service ),
			'provider'    => array( '@id' => home_url( '/#organization' ) ),
		);
	}
	if ( ! $plugin && is_singular( 'post' ) ) {
		$graph[] = aerodallas_schema_article( get_queried_object() );
	}
	if ( is_front_page() || is_page_template( 'page-templates/contact.php' ) ) {
		$faq = aerodallas_schema_faq();
		if ( $faq ) {
			$graph[] = $faq;
		}
	}

	if ( ! $graph ) {
		return;
	}
	$json = wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => array_values( $graph ),
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP
	);
	echo '<script type="application/ld+json">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded with HEX_TAG.
}
add_action( 'wp_head', 'aerodallas_schema', 20 );
