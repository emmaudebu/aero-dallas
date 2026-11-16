<?php
/**
 * Small helpers shared by templates and modules.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Company detail defaults. Only confirmed facts have a default; everything
 * else starts empty and therefore does not render.
 *
 * @return array<string,string>
 */
function aerodallas_detail_defaults() {
	return array(
		'phone'         => '+1 216 477 72 37',
		'email'         => '',
		'address'       => '',
		'hours'         => '',
		'instagram_url' => 'https://www.instagram.com/aerodallasindustrial/',
		'regions'       => '',
		'quote_email'   => '',
	);
}

/**
 * Read a company detail from the Customizer.
 *
 * @param string $key Detail key.
 * @return string
 */
function aerodallas_detail( $key ) {
	$defaults = aerodallas_detail_defaults();
	$value    = get_theme_mod( 'aerodallas_' . $key, $defaults[ $key ] ?? '' );
	return is_string( $value ) ? trim( $value ) : '';
}

/**
 * Phone number as a tel: URI.
 *
 * @return string Empty when no phone is set.
 */
function aerodallas_phone_href() {
	$phone = aerodallas_detail( 'phone' );
	if ( '' === $phone ) {
		return '';
	}
	$digits = preg_replace( '/[^\d+]/', '', $phone );
	return 'tel:' . $digits;
}

/**
 * Instagram handle derived from the profile URL, e.g. "@aerodallasindustrial".
 *
 * @return string
 */
function aerodallas_instagram_handle() {
	$url = aerodallas_detail( 'instagram_url' );
	if ( '' === $url ) {
		return '';
	}
	$path = trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
	$slug = $path ? explode( '/', $path )[0] : '';
	return $slug ? '@' . $slug : __( 'Instagram', 'aero-dallas' );
}

/**
 * Address of the notification inbox for quote requests.
 *
 * @return string
 */
function aerodallas_quote_recipient() {
	$email = sanitize_email( aerodallas_detail( 'quote_email' ) );
	return $email ? $email : get_option( 'admin_email' );
}

/**
 * Keys of the pages the theme manages, mapped to slug and template.
 *
 * @return array<string,array{slug:string,template:string}>
 */
function aerodallas_page_map() {
	return array(
		'home'     => array( 'slug' => 'home', 'template' => '' ),
		'about'    => array( 'slug' => 'about-us', 'template' => 'page-templates/about.php' ),
		'services' => array( 'slug' => 'services', 'template' => 'page-templates/services.php' ),
		'process'  => array( 'slug' => 'how-we-work', 'template' => 'page-templates/how-we-work.php' ),
		'policies' => array( 'slug' => 'policies', 'template' => 'page-templates/policies.php' ),
		'contact'  => array( 'slug' => 'contact-us', 'template' => 'page-templates/contact.php' ),
	);
}

/**
 * ID of a theme-managed page.
 *
 * @param string $key Page key from aerodallas_page_map().
 * @return int 0 when missing.
 */
function aerodallas_page_id( $key ) {
	$ids = get_option( 'aerodallas_page_ids', array() );
	if ( ! empty( $ids[ $key ] ) && 'page' === get_post_type( $ids[ $key ] ) && 'trash' !== get_post_status( $ids[ $key ] ) ) {
		return (int) $ids[ $key ];
	}
	$map  = aerodallas_page_map();
	$page = isset( $map[ $key ] ) ? get_page_by_path( $map[ $key ]['slug'] ) : null;
	return $page ? (int) $page->ID : 0;
}

/**
 * Permalink of a theme-managed page, with an optional fragment.
 *
 * @param string $key      Page key.
 * @param string $fragment Anchor without '#'.
 * @return string
 */
function aerodallas_page_url( $key, $fragment = '' ) {
	if ( 'home' === $key ) {
		$url = home_url( '/' );
	} else {
		$id  = aerodallas_page_id( $key );
		$url = $id ? get_permalink( $id ) : home_url( '/' . ( aerodallas_page_map()[ $key ]['slug'] ?? '' ) . '/' );
	}
	return $fragment ? $url . '#' . $fragment : $url;
}

/**
 * URL of the quote form, optionally pre-selecting a service.
 *
 * @param string $service Service key (anchor), e.g. "aviation".
 * @return string
 */
function aerodallas_quote_url( $service = '' ) {
	$url = aerodallas_page_url( 'contact' );
	if ( $service ) {
		$url = add_query_arg( 'service', rawurlencode( $service ), $url );
	}
	return $url . '#quote';
}

/**
 * Page section field with fallback to its default while it has never been saved.
 *
 * @param string   $key     Field key (without prefix).
 * @param int|null $post_id Page ID; defaults to the current post.
 * @return string
 */
function aerodallas_field( $key, $post_id = null ) {
	$post_id  = $post_id ? $post_id : get_the_ID();
	$meta_key = '_aerodallas_' . $key;
	if ( $post_id && metadata_exists( 'post', $post_id, $meta_key ) ) {
		return (string) get_post_meta( $post_id, $meta_key, true );
	}
	$defaults = aerodallas_field_defaults( $post_id );
	return $defaults[ $key ] ?? '';
}

/**
 * Bundled photo catalogue: key => alt text. Files live in assets/img/photos as
 * {key}-{width}.webp.
 *
 * @return array<string,string>
 */
function aerodallas_photos() {
	return array(
		'hero-ground-power'  => __( 'Close-up of an aircraft nose gear on the apron with ground equipment connected', 'aero-dallas' ),
		'service-industrial' => __( 'Technician grinding a steel component in an industrial workshop', 'aero-dallas' ),
		'service-aviation'   => __( 'Aircraft tug pushing back a wide-body airliner on the apron', 'aero-dallas' ),
		'service-parts'      => __( 'Warehouse racking stocked with parts and equipment, with a forklift in the aisle', 'aero-dallas' ),
		'service-logistics'  => __( 'Air cargo containers being moved on an airport apron', 'aero-dallas' ),
		'about-welding'      => __( 'Welder joining steel with sparks flying', 'aero-dallas' ),
		'detail-engine'      => __( 'Front view of a jet engine fan', 'aero-dallas' ),
		'hero-jet'           => __( 'Jet aircraft parked on the apron at sunset', 'aero-dallas' ),
		'band-night-gate'    => __( 'Airliner at the gate at night with ground crew and equipment', 'aero-dallas' ),
		'band-truck-dusk'    => __( 'Freight truck on the highway at dusk', 'aero-dallas' ),
	);
}

/**
 * Render an image: a media library attachment when set, otherwise a bundled photo.
 *
 * @param int    $attachment_id Attachment ID (0 for bundled).
 * @param string $photo         Bundled photo key used as fallback.
 * @param array  $args {
 *     @type string $sizes   The sizes attribute.
 *     @type string $class   Class attribute.
 *     @type bool   $eager   Load eagerly with high priority (above the fold).
 *     @type string $alt     Alt override.
 * }
 * @return string HTML.
 */
function aerodallas_image( $attachment_id, $photo, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'sizes' => '100vw',
			'class' => '',
			'eager' => false,
			'alt'   => null,
		)
	);

	if ( $attachment_id && wp_attachment_is_image( $attachment_id ) ) {
		$attr = array(
			'class'   => $args['class'],
			'sizes'   => $args['sizes'],
			'loading' => $args['eager'] ? 'eager' : 'lazy',
		);
		if ( $args['eager'] ) {
			$attr['fetchpriority'] = 'high';
		}
		if ( null !== $args['alt'] ) {
			$attr['alt'] = $args['alt'];
		}
		return wp_get_attachment_image( $attachment_id, 'full', false, $attr );
	}

	$photos = aerodallas_photos();
	if ( ! isset( $photos[ $photo ] ) ) {
		return '';
	}

	$srcset = array();
	$first  = '';
	$dims   = null;
	foreach ( array( 768, 480, 1024, 1600, 1920 ) as $w ) {
		$rel = 'assets/img/photos/' . $photo . '-' . $w . '.webp';
		if ( ! file_exists( AERODALLAS_DIR . '/' . $rel ) ) {
			continue;
		}
		$srcset[] = esc_url( AERODALLAS_URI . '/' . $rel ) . ' ' . $w . 'w';
		$first    = $first ? $first : AERODALLAS_URI . '/' . $rel;
		$dims     = $dims ? $dims : wp_getimagesize( AERODALLAS_DIR . '/' . $rel );
	}
	if ( ! $srcset ) {
		return '';
	}

	return sprintf(
		'<img src="%1$s" srcset="%2$s" sizes="%3$s" width="%4$d" height="%5$d" alt="%6$s" class="%7$s" decoding="async" loading="%8$s"%9$s>',
		esc_url( $first ),
		implode( ', ', $srcset ),
		esc_attr( $args['sizes'] ),
		(int) ( $dims[0] ?? 640 ),
		(int) ( $dims[1] ?? 427 ),
		esc_attr( null !== $args['alt'] ? $args['alt'] : $photos[ $photo ] ),
		esc_attr( $args['class'] ),
		$args['eager'] ? 'eager' : 'lazy',
		$args['eager'] ? ' fetchpriority="high"' : ''
	);
}

/**
 * Query a theme content type in admin-defined order.
 *
 * @param string $type Post type.
 * @return WP_Post[]
 */
function aerodallas_items( $type ) {
	return get_posts(
		array(
			'post_type'        => $type,
			'post_status'      => 'publish',
			'posts_per_page'   => 50,
			'orderby'          => array(
				'menu_order' => 'ASC',
				'ID'         => 'ASC',
			),
			'suppress_filters' => false,
		)
	);
}

/**
 * Service options for the quote form: anchor => label.
 *
 * @return array<string,string>
 */
function aerodallas_service_options() {
	$options = array();
	foreach ( aerodallas_items( 'aerodallas_service' ) as $service ) {
		$key = get_post_meta( $service->ID, '_aerodallas_anchor', true );
		$key = $key ? $key : $service->post_name;
		$options[ $key ] = get_the_title( $service );
	}
	$options['other'] = __( 'Other / Not sure', 'aero-dallas' );
	return $options;
}

/**
 * Uppercase letter for a zero-based index (A, B, C ...).
 *
 * @param int $index Index.
 * @return string
 */
function aerodallas_letter( $index ) {
	return chr( 65 + ( $index % 26 ) );
}

/**
 * Eyebrow label with the logo-style rules either side.
 *
 * @param string $text  Label text.
 * @param string $class Extra class.
 */
function aerodallas_eyebrow( $text, $class = '' ) {
	if ( '' === trim( $text ) ) {
		return;
	}
	printf( '<p class="eyebrow %2$s">%1$s</p>', esc_html( $text ), esc_attr( $class ) );
}

/**
 * Give every H2 an id and collect them for the table of contents.
 *
 * @param string $html Rendered content.
 * @return array{0:string,1:array<int,array{id:string,text:string}>}
 */
function aerodallas_policies_toc( $html ) {
	$toc  = array();
	$used = array();
	$html = preg_replace_callback(
		'#<h2([^>]*)>(.*?)</h2>#is',
		static function ( $m ) use ( &$toc, &$used ) {
			$attrs = $m[1];
			$text  = trim( wp_strip_all_tags( $m[2] ) );
			if ( preg_match( '/\sid=["\']([^"\']+)["\']/', $attrs, $id_match ) ) {
				$id = $id_match[1];
			} else {
				$id    = sanitize_title( $text );
				$base  = $id;
				$n     = 2;
				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $n++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}
			$used[ $id ] = true;
			$toc[]       = array(
				'id'   => $id,
				'text' => $text,
			);
			return '<h2' . $attrs . '>' . $m[2] . '</h2>';
		},
		$html
	);
	return array( $html, $toc );
}

/**
 * Heading text with an optional highlighted phrase wrapped in an accent span.
 *
 * @param string $text      Full heading.
 * @param string $highlight Phrase inside the heading to highlight.
 * @return string Escaped HTML.
 */
function aerodallas_highlight( $text, $highlight ) {
	$text      = (string) $text;
	$highlight = trim( (string) $highlight );
	if ( '' === $highlight || false === strpos( $text, $highlight ) ) {
		return esc_html( $text );
	}
	$pos = strpos( $text, $highlight );
	return esc_html( substr( $text, 0, $pos ) ) . '<span class="accent">' . esc_html( $highlight ) . '</span>' . esc_html( substr( $text, $pos + strlen( $highlight ) ) );
}

/**
 * Default colour mode chosen in the Customizer: light, dark or system.
 *
 * @return string
 */
function aerodallas_default_mode() {
	$mode = get_theme_mod( 'aerodallas_default_mode', 'light' );
	return in_array( $mode, array( 'light', 'dark', 'system' ), true ) ? $mode : 'light';
}

/**
 * Filled-in statistics from the Customizer.
 *
 * @return array<int,array{value:string,label:string}>
 */
function aerodallas_stats() {
	$stats = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$value = trim( (string) get_theme_mod( 'aerodallas_stat_' . $i . '_value', '' ) );
		$label = trim( (string) get_theme_mod( 'aerodallas_stat_' . $i . '_label', '' ) );
		if ( '' !== $value && '' !== $label ) {
			$stats[] = array(
				'value' => $value,
				'label' => $label,
			);
		}
	}
	return $stats;
}

/**
 * Icon for an item, falling back to a default list by position.
 *
 * @param int      $post_id  Item ID.
 * @param int      $index    Zero-based position.
 * @param string[] $fallback Icons by position.
 * @return string Icon name.
 */
function aerodallas_item_icon( $post_id, $index, $fallback ) {
	$icon = (string) get_post_meta( $post_id, '_aerodallas_icon', true );
	return $icon ? $icon : ( $fallback[ $index % count( $fallback ) ] ?? 'check' );
}
