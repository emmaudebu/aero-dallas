<?php
/**
 * Theme activation: creates the six pages with their templates, sets the
 * static front page, builds the menus and seeds the editable content.
 * Every step checks for existing data first, so activating twice is safe.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Wrap plain paragraphs in paragraph blocks.
 *
 * @param string[] $paragraphs Paragraph texts.
 * @return string
 */
function aerodallas_blocks_paragraphs( $paragraphs ) {
	$out = '';
	foreach ( $paragraphs as $p ) {
		$out .= "<!-- wp:paragraph -->\n<p>" . esc_html( $p ) . "</p>\n<!-- /wp:paragraph -->\n\n";
	}
	return $out;
}

/**
 * Heading block with an anchor.
 *
 * @param string $text   Heading text.
 * @param string $anchor Anchor.
 * @return string
 */
function aerodallas_blocks_heading( $text, $anchor ) {
	return '<!-- wp:heading {"anchor":"' . esc_attr( $anchor ) . '"} -->' . "\n" .
		'<h2 class="wp-block-heading" id="' . esc_attr( $anchor ) . '">' . esc_html( $text ) . "</h2>\n<!-- /wp:heading -->\n\n";
}

/**
 * List block.
 *
 * @param string[] $items Items.
 * @return string
 */
function aerodallas_blocks_list( $items ) {
	$out = "<!-- wp:list -->\n<ul class=\"wp-block-list\">";
	foreach ( $items as $item ) {
		$out .= "<!-- wp:list-item -->\n<li>" . esc_html( $item ) . "</li>\n<!-- /wp:list-item -->";
	}
	return $out . "</ul>\n<!-- /wp:list -->\n\n";
}

/**
 * Default page definitions.
 *
 * @return array<string,array{title:string,content:string,seo:string}>
 */
function aerodallas_default_pages() {
	$policies  = aerodallas_blocks_heading( __( 'A. General Business Policy', 'aero-dallas' ), 'general' );
	$policies .= aerodallas_blocks_paragraphs( array( __( 'AERO DALLAS is committed to conducting business with professionalism, integrity, transparency, and accountability. Client engagements are handled based on agreed requirements, approved quotations, and mutually accepted terms and conditions. We reserve the right to review service requests based on technical feasibility, equipment availability, and operational capacity.', 'aero-dallas' ) ) );
	$policies .= aerodallas_blocks_heading( __( 'B. Quotation & Payment Policy', 'aero-dallas' ), 'quotation-payment' );
	$policies .= aerodallas_blocks_list(
		array(
			__( 'All services and equipment supplies are subject to quotation.', 'aero-dallas' ),
			__( 'Quotations will outline scope of work, pricing, and applicable terms.', 'aero-dallas' ),
			__( 'Work or supply orders proceed after required client approval.', 'aero-dallas' ),
			__( 'Payment terms are communicated and agreed before commencement.', 'aero-dallas' ),
			__( 'Additional work outside the approved scope requires further agreement.', 'aero-dallas' ),
			__( 'Applicable taxes, shipping, transportation, and other charges will be stated where relevant.', 'aero-dallas' ),
		)
	);
	$policies .= aerodallas_blocks_heading( __( 'C. Service & Delivery Policy', 'aero-dallas' ), 'service-delivery' );
	$policies .= aerodallas_blocks_list(
		array(
			__( 'Service timelines are agreed upon before commencement.', 'aero-dallas' ),
			__( 'Schedules may depend on equipment availability, parts sourcing, logistics, and technical requirements.', 'aero-dallas' ),
			__( 'Clients will be informed of significant changes to agreed timelines.', 'aero-dallas' ),
			__( 'Equipment and services will be supplied or delivered according to approved specifications and scope.', 'aero-dallas' ),
			__( 'AERO DALLAS will make reasonable efforts to ensure professional and dependable service delivery.', 'aero-dallas' ),
		)
	);
	$policies .= aerodallas_blocks_heading( __( 'D. Cancellation & Refund Policy', 'aero-dallas' ), 'cancellation-refund' );
	$policies .= aerodallas_blocks_list(
		array(
			__( 'Cancellation requests must be communicated formally.', 'aero-dallas' ),
			__( 'Cancellation terms depend on the nature of the service or supply order.', 'aero-dallas' ),
			__( 'Costs incurred for procurement, logistics, labour, or specialised sourcing may be non-refundable where agreed in advance and permitted by applicable law.', 'aero-dallas' ),
			__( 'Refunds, where applicable, will be processed according to agreed terms.', 'aero-dallas' ),
			__( 'Custom-sourced equipment and special orders may be subject to specific cancellation restrictions.', 'aero-dallas' ),
		)
	);
	$policies .= aerodallas_blocks_heading( __( 'E. Warranty & After-Sales Policy', 'aero-dallas' ), 'warranty' );
	$policies .= aerodallas_blocks_list(
		array(
			__( 'Warranty coverage, where applicable, will be communicated at point of sale or service agreement.', 'aero-dallas' ),
			__( 'Warranty terms may vary by equipment, manufacturer, supplier, or repair type.', 'aero-dallas' ),
			__( 'Warranty claims must be supported by relevant purchase or service documentation.', 'aero-dallas' ),
			__( 'Misuse, unauthorised modifications, improper handling, or failure to follow operating instructions may be excluded subject to applicable warranty terms.', 'aero-dallas' ),
		)
	);
	$policies .= aerodallas_blocks_heading( __( 'F. Health, Safety & Quality Policy', 'aero-dallas' ), 'health-safety-quality' );
	$policies .= aerodallas_blocks_paragraphs( array( __( 'AERO DALLAS recognises the importance of safety, quality, and responsible operational practices in industrial and aviation support environments. We aim to promote safe working practices, maintain professional service standards, follow relevant equipment handling procedures, support proper equipment maintenance and functionality, and encourage compliance with applicable safety and operational requirements.', 'aero-dallas' ) ) );
	$policies .= aerodallas_blocks_heading( __( 'G. Privacy Policy', 'aero-dallas' ), 'privacy' );
	$policies .= aerodallas_blocks_paragraphs( array( __( 'AERO DALLAS respects the privacy of clients, business partners, and website visitors. Information submitted through the website, including names, email addresses, phone numbers, and service inquiries, will be used for business communication, quotation preparation, and service-related purposes. We aim to protect personal information, use it for legitimate business purposes, and apply appropriate safeguards. Sharing may occur where necessary for service delivery, legally required, or otherwise permitted.', 'aero-dallas' ) ) );

	return array(
		'home'     => array(
			'title'   => __( 'Home', 'aero-dallas' ),
			'content' => '',
			'seo'     => __( 'AERO DALLAS provides specialised industrial support solutions across mechanical engineering, aviation ground support, equipment supply, and industrial logistics.', 'aero-dallas' ),
		),
		'about'    => array(
			'title'   => __( 'About Us', 'aero-dallas' ),
			'content' => aerodallas_blocks_paragraphs(
				array(
					__( 'AERO DALLAS is an industrial support company providing specialised services across mechanical engineering, aviation ground support, equipment supply, and industrial logistics.', 'aero-dallas' ),
					__( 'We are committed to helping businesses maintain operational efficiency through dependable technical assistance, quality equipment supply, machinery repairs, and effective logistics solutions.', 'aero-dallas' ),
					__( 'Our approach is built on professionalism, technical competence, reliability, and a strong understanding of the operational demands of industrial and aviation environments.', 'aero-dallas' ),
					__( "At AERO DALLAS, we understand that equipment reliability and efficient operations are essential to business success. We therefore strive to provide practical, dependable, and professionally delivered support solutions tailored to our clients' needs.", 'aero-dallas' ),
					__( 'We aim to build lasting business relationships through integrity, commitment, technical excellence, and consistent service delivery.', 'aero-dallas' ),
				)
			),
			'seo'     => __( 'Who AERO DALLAS is, our vision and mission, and the core values behind our industrial and aviation support services.', 'aero-dallas' ),
		),
		'services' => array(
			'title'   => __( 'Services', 'aero-dallas' ),
			'content' => aerodallas_blocks_paragraphs( array( __( 'AERO DALLAS offers specialised services designed to support the operational needs of businesses within the industrial and aviation sectors.', 'aero-dallas' ) ) ),
			'seo'     => __( 'Industrial support, aviation ground support equipment (GPU, HPU, air starter units, aircraft jacks, tow bars), parts and equipment supply, and air and ground logistics.', 'aero-dallas' ),
		),
		'process'  => array(
			'title'   => __( 'How We Work', 'aero-dallas' ),
			'content' => aerodallas_blocks_paragraphs( array( __( 'AERO DALLAS follows a structured approach to ensure that every client receives professional attention, clear communication, and appropriate technical support.', 'aero-dallas' ) ) ),
			'seo'     => __( 'Our seven-step process: inquiry and consultation, assessment, quotation, approval, service delivery, inspection and handover, and follow-up.', 'aero-dallas' ),
		),
		'policies' => array(
			'title'   => __( 'Policies', 'aero-dallas' ),
			'content' => $policies,
			'seo'     => __( 'AERO DALLAS business, quotation and payment, service and delivery, cancellation and refund, warranty, health and safety, and privacy policies.', 'aero-dallas' ),
		),
		'contact'  => array(
			'title'   => __( 'Contact Us', 'aero-dallas' ),
			'content' => aerodallas_blocks_paragraphs( array( __( 'Whether you require industrial machinery repairs, aviation ground support equipment, parts supply, or logistics assistance, AERO DALLAS is ready to discuss your requirements. Our team is committed to providing professional responses and dependable support tailored to your business needs.', 'aero-dallas' ) ) ),
			'seo'     => __( 'Contact AERO DALLAS or request a quote for industrial machinery repairs, aviation ground support equipment, parts supply, or logistics.', 'aero-dallas' ),
		),
	);
}

/**
 * Seed data for the content types.
 *
 * @return array<string,array[]>
 */
function aerodallas_default_items() {
	$ul = static function ( $items ) {
		return "<ul>\n<li>" . implode( "</li>\n<li>", array_map( 'esc_html', $items ) ) . "</li>\n</ul>";
	};

	return array(
		'aerodallas_service' => array(
			array(
				'title'   => __( 'Industrial Support Services', 'aero-dallas' ),
				'excerpt' => __( 'Technical assistance, machinery repairs, and equipment maintenance.', 'aero-dallas' ),
				'content' => $ul(
					array(
						__( 'Mechanical engineering support', 'aero-dallas' ),
						__( 'Industrial machinery repairs', 'aero-dallas' ),
						__( 'Equipment maintenance', 'aero-dallas' ),
						__( 'Industrial spare parts supply', 'aero-dallas' ),
						__( 'Mechanical equipment support', 'aero-dallas' ),
						__( 'Industrial logistics services', 'aero-dallas' ),
					)
				),
				'meta'    => array(
					'card_title' => __( 'Mechanical Engineering Support', 'aero-dallas' ),
					'anchor'     => 'industrial',
					'icon'       => 'gear',
					'photo'      => 'service-industrial',
				),
			),
			array(
				'title'   => __( 'Aviation Ground Support Services', 'aero-dallas' ),
				'excerpt' => __( 'Repairs, supply, and support for essential aviation ground equipment.', 'aero-dallas' ),
				'content' => $ul(
					array(
						__( 'Ground support equipment repairs and supply', 'aero-dallas' ),
						__( 'Ground Power Units (GPU)', 'aero-dallas' ),
						__( 'Hydraulic Power Units (HPU)', 'aero-dallas' ),
						__( 'Air Starter Units', 'aero-dallas' ),
						__( 'Aircraft Jacks', 'aero-dallas' ),
						__( 'Aircraft Tow Bars', 'aero-dallas' ),
						__( 'Aviation equipment support', 'aero-dallas' ),
					)
				),
				'meta'    => array(
					'card_title' => __( 'Aviation Ground Support', 'aero-dallas' ),
					'anchor'     => 'aviation',
					'icon'       => 'plane',
					'photo'      => 'service-aviation',
				),
			),
			array(
				'title'   => __( 'Parts & Equipment Supply', 'aero-dallas' ),
				'excerpt' => __( 'Supply of parts and equipment to meet operational requirements.', 'aero-dallas' ),
				'content' => '<p>' . esc_html__( 'We support businesses with the supply of industrial and aviation-related parts and equipment required for maintenance, repairs, and operational support. Our focus is on identifying suitable equipment and components based on client specifications and operational requirements.', 'aero-dallas' ) . '</p>',
				'meta'    => array(
					'card_title' => __( 'Parts & Equipment Supply', 'aero-dallas' ),
					'anchor'     => 'parts-supply',
					'icon'       => 'parts',
					'photo'      => 'service-parts',
				),
			),
			array(
				'title'   => __( 'Air & Ground Logistics Services', 'aero-dallas' ),
				'excerpt' => __( 'Air and ground logistics support for industrial and operational needs.', 'aero-dallas' ),
				'content' => '<p>' . esc_html__( 'AERO DALLAS provides air and ground logistics support to facilitate the movement and coordination of industrial equipment, parts, and operational supplies. Our logistics services are structured to support efficient handling and dependable coordination based on project requirements.', 'aero-dallas' ) . '</p>',
				'meta'    => array(
					'card_title' => __( 'Industrial Logistics', 'aero-dallas' ),
					'anchor'     => 'logistics',
					'icon'       => 'truck',
					'photo'      => 'service-logistics',
				),
			),
		),
		'aerodallas_step'    => array(
			array( 'title' => __( 'Client Inquiry & Consultation', 'aero-dallas' ), 'excerpt' => __( 'Clients contact us with equipment requirements, technical challenges, logistics needs, or service requests.', 'aero-dallas' ), 'meta' => array( 'icon' => 'chat' ) ),
			array( 'title' => __( 'Assessment of Requirements', 'aero-dallas' ), 'excerpt' => __( 'We review client specifications, equipment details, scope of work, and operational expectations.', 'aero-dallas' ), 'meta' => array( 'icon' => 'magnifier' ) ),
			array( 'title' => __( 'Quotation & Proposal', 'aero-dallas' ), 'excerpt' => __( 'A quotation or service proposal is prepared based on requirements, availability of parts or equipment, and scope of work.', 'aero-dallas' ), 'meta' => array( 'icon' => 'document' ) ),
			array( 'title' => __( 'Approval & Confirmation', 'aero-dallas' ), 'excerpt' => __( "Work commences following the client's acceptance of the quotation and agreed terms.", 'aero-dallas' ), 'meta' => array( 'icon' => 'clipboard' ) ),
			array( 'title' => __( 'Service Delivery', 'aero-dallas' ), 'excerpt' => __( 'The agreed repairs, equipment supply, technical support, or logistics service is carried out according to the confirmed scope.', 'aero-dallas' ), 'meta' => array( 'icon' => 'wrench' ) ),
			array( 'title' => __( 'Inspection & Handover', 'aero-dallas' ), 'excerpt' => __( 'Where applicable, completed work or supplied equipment is reviewed before handover to the client.', 'aero-dallas' ), 'meta' => array( 'icon' => 'handover' ) ),
			array( 'title' => __( 'Client Support & Follow-Up', 'aero-dallas' ), 'excerpt' => __( 'Professional communication is maintained regarding completed services and any agreed follow-up requirements.', 'aero-dallas' ), 'meta' => array( 'icon' => 'headset' ) ),
		),
		'aerodallas_value'   => array(
			array( 'title' => __( 'Reliability', 'aero-dallas' ), 'excerpt' => __( 'Maintaining consistency and dependability in every service delivered.', 'aero-dallas' ), 'meta' => array( 'icon' => 'shield' ) ),
			array( 'title' => __( 'Professionalism', 'aero-dallas' ), 'excerpt' => __( 'Upholding high standards in business operations and client relationships.', 'aero-dallas' ), 'meta' => array( 'icon' => 'briefcase' ) ),
			array( 'title' => __( 'Technical Excellence', 'aero-dallas' ), 'excerpt' => __( 'Applying knowledge, expertise, and precision to industrial support solutions.', 'aero-dallas' ), 'meta' => array( 'icon' => 'target' ) ),
			array( 'title' => __( 'Efficiency', 'aero-dallas' ), 'excerpt' => __( 'Promoting timely service delivery and effective operational processes.', 'aero-dallas' ), 'meta' => array( 'icon' => 'gauge' ) ),
			array( 'title' => __( 'Integrity', 'aero-dallas' ), 'excerpt' => __( 'Conducting business with honesty, accountability, and transparency.', 'aero-dallas' ), 'meta' => array( 'icon' => 'scale' ) ),
			array( 'title' => __( 'Commitment', 'aero-dallas' ), 'excerpt' => __( "Remaining dedicated to meeting clients' operational requirements.", 'aero-dallas' ), 'meta' => array( 'icon' => 'flag' ) ),
		),
		'aerodallas_reason'  => array(
			array( 'title' => __( 'Professional industrial and aviation support solutions', 'aero-dallas' ) ),
			array( 'title' => __( 'Technical expertise and practical assistance', 'aero-dallas' ) ),
			array( 'title' => __( 'Commitment to reliability and efficiency', 'aero-dallas' ) ),
			array( 'title' => __( 'Client-focused service delivery', 'aero-dallas' ) ),
			array( 'title' => __( 'Transparent communication', 'aero-dallas' ) ),
			array( 'title' => __( 'Dedicated support for operational requirements', 'aero-dallas' ) ),
		),
		'aerodallas_faq'     => array(
			array( 'title' => __( 'What services does AERO DALLAS provide?', 'aero-dallas' ), 'content' => __( 'Mechanical engineering support, machinery repairs, parts supply, aviation ground support equipment repairs and supply, and air and ground logistics services.', 'aero-dallas' ) ),
			array( 'title' => __( 'Can I request a quotation for equipment supply?', 'aero-dallas' ), 'content' => __( 'Yes. Submit your requirements and specifications through the website to request a quotation.', 'aero-dallas' ) ),
			array( 'title' => __( 'Does AERO DALLAS provide machinery repairs?', 'aero-dallas' ), 'content' => __( 'Yes. Industrial machinery repair and mechanical engineering support are offered subject to equipment type and technical assessment.', 'aero-dallas' ) ),
			array( 'title' => __( 'Do you supply aviation ground support equipment?', 'aero-dallas' ), 'content' => __( 'We support supply of ground power units, hydraulic power units, air starter units, aircraft jacks, and aircraft tow bars, subject to availability and specifications.', 'aero-dallas' ) ),
			array( 'title' => __( 'How do I engage your services?', 'aero-dallas' ), 'content' => __( 'Contact us by phone, Instagram, or the website inquiry form. Our team will review your requirements and guide you through the next steps.', 'aero-dallas' ) ),
		),
	);
}

/**
 * Create missing pages and remember their IDs.
 *
 * @return array<string,int>
 */
function aerodallas_setup_pages() {
	$ids   = (array) get_option( 'aerodallas_page_ids', array() );
	$pages = aerodallas_default_pages();

	foreach ( aerodallas_page_map() as $key => $def ) {
		$id = aerodallas_page_id( $key );
		if ( ! $id ) {
			$id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => $pages[ $key ]['title'],
					'post_name'    => $def['slug'],
					'post_content' => $pages[ $key ]['content'],
					'menu_order'   => count( $ids ),
				)
			);
			if ( is_wp_error( $id ) || ! $id ) {
				continue;
			}
			update_post_meta( $id, '_aerodallas_seo_description', $pages[ $key ]['seo'] );
		}
		if ( $def['template'] && ! get_page_template_slug( $id ) ) {
			update_post_meta( $id, '_wp_page_template', $def['template'] );
		}
		$ids[ $key ] = (int) $id;
	}

	update_option( 'aerodallas_page_ids', $ids, false );

	if ( ! empty( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
	}
	return $ids;
}

/**
 * Create the primary and footer menus if they do not exist, and assign them.
 *
 * @param array<string,int> $ids Page IDs.
 */
function aerodallas_setup_menus( $ids ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menus     = array(
		'primary' => __( 'Primary Menu', 'aero-dallas' ),
		'footer'  => __( 'Footer Menu', 'aero-dallas' ),
	);

	foreach ( $menus as $location => $name ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			continue;
		}
		$menu = wp_get_nav_menu_object( $name );
		if ( ! $menu ) {
			$menu_id = wp_create_nav_menu( $name );
			if ( is_wp_error( $menu_id ) ) {
				continue;
			}
			foreach ( array( 'home', 'about', 'services', 'process', 'policies', 'contact' ) as $position => $key ) {
				if ( empty( $ids[ $key ] ) ) {
					continue;
				}
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-object-id' => $ids[ $key ],
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
						'menu-item-position'  => $position + 1,
						'menu-item-title'     => 'home' === $key ? __( 'Home', 'aero-dallas' ) : '',
					)
				);
			}
		} else {
			$menu_id = $menu->term_id;
		}
		$locations[ $location ] = (int) $menu_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Seed each content type once, only if it has no items at all.
 */
function aerodallas_setup_items() {
	foreach ( aerodallas_default_items() as $type => $items ) {
		$flag = 'aerodallas_seeded_' . $type;
		if ( get_option( $flag ) ) {
			continue;
		}
		$existing = get_posts(
			array(
				'post_type'      => $type,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( ! $existing ) {
			foreach ( $items as $order => $item ) {
				if ( 'aerodallas_service' === $type ) {
					$item = aerodallas_with_service_details( $item );
				}
				$id = wp_insert_post(
					array(
						'post_type'    => $type,
						'post_status'  => 'publish',
						'post_name'    => $item['slug'] ?? '',
						'post_title'   => $item['title'],
						'post_excerpt' => $item['excerpt'] ?? '',
						'post_content' => $item['content'] ?? '',
						'menu_order'   => $order + 1,
					)
				);
				if ( $id && ! is_wp_error( $id ) ) {
					foreach ( $item['meta'] ?? array() as $key => $value ) {
						update_post_meta( $id, '_aerodallas_' . $key, $value );
					}
				}
			}
		}
		update_option( $flag, 1, false );
	}
}

/**
 * Merge the detailed page content into a seeded service item.
 *
 * @param array $item Seed item with meta.anchor.
 * @return array
 */
function aerodallas_with_service_details( $item ) {
	$details = aerodallas_service_details();
	$anchor  = $item['meta']['anchor'] ?? '';
	if ( ! isset( $details[ $anchor ] ) ) {
		return $item;
	}
	$d               = $details[ $anchor ];
	$item['slug']    = $d['slug'];
	$item['content'] = $d['content'];
	$item['meta']    = array_merge(
		$item['meta'],
		array(
			'intro'         => $d['intro'],
			'scope'         => implode( "\n", $d['scope'] ),
			'hero_photo'    => $d['hero_photo'],
			'gallery_photo' => $d['gallery_photo'],
		)
	);
	return $item;
}

/**
 * One-time upgrade to 1.1.0 for sites set up with 1.0.0: give each service
 * its own page slug, the detailed content and the new fields. Anything the
 * site owner has already edited (slug, content, fields) is left untouched.
 */
function aerodallas_upgrade() {
	$version = (string) get_option( 'aerodallas_setup_version', '0' );
	if ( version_compare( $version, '1.1.0', '>=' ) ) {
		return;
	}
	$normalise = static function ( $html ) {
		return trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $html ) ) );
	};
	foreach ( aerodallas_service_details() as $anchor => $d ) {
		$found = get_posts(
			array(
				'post_type'      => 'aerodallas_service',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'meta_key'       => '_aerodallas_anchor', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- one-time upgrade.
				'meta_value'     => $anchor, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		if ( ! $found ) {
			continue;
		}
		$service = $found[0];
		$update  = array( 'ID' => $service->ID );
		if ( sanitize_title( $service->post_title ) === $service->post_name ) {
			$update['post_name'] = $d['slug'];
		}
		if ( $normalise( $service->post_content ) === $normalise( $d['legacy'] ) ) {
			$update['post_content'] = $d['content'];
		}
		if ( count( $update ) > 1 ) {
			wp_update_post( wp_slash( $update ) );
		}
		$defaults = array(
			'intro'         => $d['intro'],
			'scope'         => implode( "\n", $d['scope'] ),
			'hero_photo'    => $d['hero_photo'],
			'gallery_photo' => $d['gallery_photo'],
		);
		foreach ( $defaults as $key => $value ) {
			if ( ! metadata_exists( 'post', $service->ID, '_aerodallas_' . $key ) ) {
				update_post_meta( $service->ID, '_aerodallas_' . $key, $value );
			}
		}
	}
	update_option( 'aerodallas_setup_version', AERODALLAS_VERSION, false );
	flush_rewrite_rules();
}
add_action( 'init', 'aerodallas_upgrade', 30 );

/**
 * Run all setup steps.
 */
function aerodallas_activate() {
	aerodallas_register_content_types();
	aerodallas_register_quote_request();

	$ids = aerodallas_setup_pages();
	aerodallas_setup_menus( $ids );
	aerodallas_setup_items();

	$tagline = get_option( 'blogdescription' );
	if ( '' === trim( (string) $tagline ) || 'Just another WordPress site' === $tagline ) {
		update_option( 'blogdescription', __( 'Where industrial expertise meets dependable support.', 'aero-dallas' ) );
	}
	if ( '' === (string) get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	flush_rewrite_rules();
	update_option( 'aerodallas_setup_version', AERODALLAS_VERSION, false );
}
add_action( 'after_switch_theme', 'aerodallas_activate' );

/**
 * Admin notice after activation pointing to the essentials.
 */
function aerodallas_activation_notice() {
	global $pagenow;
	if ( 'themes.php' !== $pagenow || ! isset( $_GET['activated'] ) || ! current_user_can( 'edit_theme_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	printf(
		'<div class="notice notice-success is-dismissible"><p>%1$s <a href="%2$s">%3$s</a> · <a href="%4$s">%5$s</a></p></div>',
		esc_html__( 'AERO DALLAS is set up: pages, menus and content have been created.', 'aero-dallas' ),
		esc_url( admin_url( 'customize.php?autofocus[panel]=aerodallas_company' ) ),
		esc_html__( 'Add company details', 'aero-dallas' ),
		esc_url( admin_url( 'admin.php?page=aerodallas-content' ) ),
		esc_html__( 'Edit site content', 'aero-dallas' )
	);
}
add_action( 'admin_notices', 'aerodallas_activation_notice' );
