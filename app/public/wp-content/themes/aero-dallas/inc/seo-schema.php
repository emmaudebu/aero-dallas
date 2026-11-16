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
			$desc = aerodallas_field( 'hero_lead', $id );
		}
		if ( '' === $desc ) {
			$post = get_post( $id );
			$desc = has_excerpt( $post ) ? $post->post_excerpt : wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
		}
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
		'name'   => 'AERO DALLAS',
		'url'    => home_url( '/' ),
		'logo'   => array(
			'@type'  => 'ImageObject',
			'url'    => AERODALLAS_URI . '/assets/img/brand/logo.png',
			'width'  => 640,
			'height' => 210,
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
		$anchor      = get_post_meta( $service->ID, '_aerodallas_anchor', true );
		$description = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $service->post_content ) ) );
		$nodes[]     = array(
			'@type'       => 'Service',
			'name'        => wp_strip_all_tags( get_the_title( $service ) ),
			'serviceType' => wp_strip_all_tags( get_the_title( $service ) ),
			'description' => $description ? $description : wp_strip_all_tags( $service->post_excerpt ),
			'url'         => aerodallas_page_url( 'services', $anchor ),
			'provider'    => array( '@id' => home_url( '/#organization' ) ),
		);
	}
	return $nodes;
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
			$graph[] = array(
				'@type'           => 'BreadcrumbList',
				'@id'             => get_permalink( $page_id ) . '#breadcrumb',
				'itemListElement' => array(
					array(
						'@type'    => 'ListItem',
						'position' => 1,
						'name'     => __( 'Home', 'aero-dallas' ),
						'item'     => home_url( '/' ),
					),
					array(
						'@type'    => 'ListItem',
						'position' => 2,
						'name'     => wp_strip_all_tags( get_the_title( $page_id ) ),
						'item'     => get_permalink( $page_id ),
					),
				),
			);
		}
	}

	if ( is_page_template( 'page-templates/services.php' ) ) {
		$graph = array_merge( $graph, aerodallas_schema_services() );
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
