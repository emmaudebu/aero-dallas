<?php
/**
 * Default detail content for the four service pages. Used when seeding a new
 * site and by the 1.1.0 upgrade. Copy extends the content brief without adding
 * claims: figures, certifications and guarantees are deliberately absent.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Level-3 heading block.
 *
 * @param string $text Heading text.
 * @return string
 */
function aerodallas_b_h3( $text ) {
	return "<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . esc_html( $text ) . "</h3>\n<!-- /wp:heading -->\n\n";
}

/**
 * Ordered list block.
 *
 * @param string[] $items Items.
 * @return string
 */
function aerodallas_b_ol( $items ) {
	$out = "<!-- wp:list {\"ordered\":true} -->\n<ol class=\"wp-block-list\">";
	foreach ( $items as $item ) {
		$out .= "<!-- wp:list-item -->\n<li>" . esc_html( $item ) . "</li>\n<!-- /wp:list-item -->";
	}
	return $out . "</ol>\n<!-- /wp:list -->\n\n";
}

/**
 * Service details keyed by anchor.
 *
 * @return array<string,array{slug:string,intro:string,scope:string[],hero_photo:string,gallery_photo:string,legacy:string,content:string}>
 */
function aerodallas_service_details() {
	$p  = 'aerodallas_blocks_paragraphs';
	$h2 = 'aerodallas_blocks_heading';
	$ul = 'aerodallas_blocks_list';
	$h3 = 'aerodallas_b_h3';
	$ol = 'aerodallas_b_ol';

	$commitment = __( 'We prioritise transparency, professionalism, and clear communication throughout every stage of the service process.', 'aero-dallas' );

	$industrial_scope = array(
		__( 'Mechanical engineering support', 'aero-dallas' ),
		__( 'Industrial machinery repairs', 'aero-dallas' ),
		__( 'Equipment maintenance', 'aero-dallas' ),
		__( 'Industrial spare parts supply', 'aero-dallas' ),
		__( 'Mechanical equipment support', 'aero-dallas' ),
		__( 'Industrial logistics services', 'aero-dallas' ),
	);
	$aviation_scope   = array(
		__( 'Ground support equipment repairs and supply', 'aero-dallas' ),
		__( 'Ground Power Units (GPU)', 'aero-dallas' ),
		__( 'Hydraulic Power Units (HPU)', 'aero-dallas' ),
		__( 'Air Starter Units', 'aero-dallas' ),
		__( 'Aircraft Jacks', 'aero-dallas' ),
		__( 'Aircraft Tow Bars', 'aero-dallas' ),
		__( 'Aviation equipment support', 'aero-dallas' ),
	);
	$parts_overview   = __( 'We support businesses with the supply of industrial and aviation-related parts and equipment required for maintenance, repairs, and operational support. Our focus is on identifying suitable equipment and components based on client specifications and operational requirements.', 'aero-dallas' );
	$logistics_overview = __( 'AERO DALLAS INDUSTRIAL provides air and ground logistics support to facilitate the movement and coordination of industrial equipment, parts, and operational supplies. Our logistics services are structured to support efficient handling and dependable coordination based on project requirements.', 'aero-dallas' );

	return array(
		'industrial'   => array(
			'slug'          => 'industrial-support',
			'intro'         => __( 'Mechanical engineering support, industrial machinery repairs and equipment maintenance that help businesses keep their operations running efficiently.', 'aero-dallas' ),
			'scope'         => $industrial_scope,
			'hero_photo'    => 'eq-cnc-milling',
			'gallery_photo' => 'about-welding',
			'legacy'        => implode( ' ', $industrial_scope ),
			'content'       =>
				$h2( __( 'Overview', 'aero-dallas' ), 'overview' ) .
				$p( array( __( 'AERO DALLAS INDUSTRIAL provides specialised industrial support across mechanical engineering, machinery repairs, equipment maintenance and spare parts supply. Every request starts with a review of your equipment, its condition and your operational requirements, so that the work proposed is practical, clearly scoped and agreed before anything begins.', 'aero-dallas' ) ) ) .
				$h2( __( 'What we support', 'aero-dallas' ), 'what-we-support' ) .
				$h3( __( 'Mechanical engineering support', 'aero-dallas' ) ) .
				$p( array( __( 'Technical assistance to help identify equipment faults, assess repair or replacement options and plan the work needed to restore dependable operation.', 'aero-dallas' ) ) ) .
				$h3( __( 'Industrial machinery repairs', 'aero-dallas' ) ) .
				$p( array( __( 'Repairs are carried out according to the agreed scope, following an assessment of the equipment type and its condition. Where parts are needed, sourcing and lead times are confirmed in the quotation.', 'aero-dallas' ) ) ) .
				$h3( __( 'Equipment maintenance', 'aero-dallas' ) ) .
				$p( array( __( 'Maintenance support that helps keep equipment working as intended and reduces the risk of unplanned downtime. The maintenance required is agreed with you based on the equipment and how it is used.', 'aero-dallas' ) ) ) .
				$h3( __( 'Industrial spare parts supply', 'aero-dallas' ) ) .
				$p( array( __( 'Supply of spare parts matched to your specifications, equipment details and operational requirements.', 'aero-dallas' ) ) ) .
				$h3( __( 'Mechanical equipment support', 'aero-dallas' ) ) .
				$p( array( __( 'Practical support for mechanical equipment in service, from troubleshooting faults to planning follow-up work.', 'aero-dallas' ) ) ) .
				$h3( __( 'Industrial logistics services', 'aero-dallas' ) ) .
				$p( array( __( 'Where equipment or parts need to be moved, air and ground logistics can be coordinated as part of the same engagement.', 'aero-dallas' ) ) ) .
				$h2( __( 'What to include in your request', 'aero-dallas' ), 'request' ) .
				$ul(
					array(
						__( 'Equipment type, make and model, and serial number if available', 'aero-dallas' ),
						__( 'A description of the fault, requirement or work needed', 'aero-dallas' ),
						__( 'Photos, drawings or specifications, if you have them', 'aero-dallas' ),
						__( 'Where the equipment is located and any access requirements', 'aero-dallas' ),
						__( 'Your preferred timescale', 'aero-dallas' ),
					)
				) .
				$h2( __( 'Our commitment', 'aero-dallas' ), 'commitment' ) .
				$p( array( $commitment ) ),
		),
		'aviation'     => array(
			'slug'          => 'aviation-ground-support',
			'intro'         => __( 'Repairs, supply and support for essential aviation ground support equipment, from ground power and hydraulic units to aircraft jacks and tow bars.', 'aero-dallas' ),
			'scope'         => $aviation_scope,
			'hero_photo'    => 'eq-pushback-tractor',
			'gallery_photo' => 'hero-ground-power',
			'legacy'        => implode( ' ', $aviation_scope ),
			'content'       =>
				$h2( __( 'Overview', 'aero-dallas' ), 'overview' ) .
				$p( array( __( 'Ground support equipment keeps aircraft powered, serviced and moving safely on the ground. AERO DALLAS INDUSTRIAL supports the repair and supply of essential aviation ground equipment, subject to availability and specifications, so that your operation can keep its ground equipment available and in working order.', 'aero-dallas' ) ) ) .
				$h2( __( 'Equipment we support', 'aero-dallas' ), 'equipment' ) .
				$h3( __( 'Ground Power Units (GPU)', 'aero-dallas' ) ) .
				$p( array( __( 'Ground power units supply electrical power to an aircraft on the ground while its engines are shut down, supporting servicing, checks and turnaround activity.', 'aero-dallas' ) ) ) .
				$h3( __( 'Hydraulic Power Units (HPU)', 'aero-dallas' ) ) .
				$p( array( __( "Hydraulic power units provide hydraulic pressure to an aircraft's systems on the ground, for example during maintenance, testing and servicing.", 'aero-dallas' ) ) ) .
				$h3( __( 'Air Starter Units', 'aero-dallas' ) ) .
				$p( array( __( "Air starter units deliver the compressed air used to start an aircraft's engines when onboard air is not available.", 'aero-dallas' ) ) ) .
				$h3( __( 'Aircraft Jacks', 'aero-dallas' ) ) .
				$p( array( __( 'Aircraft jacks lift and support an aircraft or its landing gear for maintenance, inspections and wheel or tyre changes.', 'aero-dallas' ) ) ) .
				$h3( __( 'Aircraft Tow Bars', 'aero-dallas' ) ) .
				$p( array( __( "Tow bars connect a tug or tractor to an aircraft's landing gear for towing and pushback. The right tow bar depends on the aircraft type.", 'aero-dallas' ) ) ) .
				$h2( __( 'Repairs and supply', 'aero-dallas' ), 'repairs-supply' ) .
				$p(
					array(
						__( 'Ground support equipment repairs are scoped after an assessment of the unit and its condition, and carried out according to the confirmed scope.', 'aero-dallas' ),
						__( 'For supply, we work from your specifications and operational requirements to identify suitable equipment. The quotation states availability, pricing and any applicable charges.', 'aero-dallas' ),
					)
				) .
				$h2( __( 'What to include in your request', 'aero-dallas' ), 'request' ) .
				$ul(
					array(
						__( 'Equipment type, manufacturer and model', 'aero-dallas' ),
						__( 'The aircraft types the equipment needs to support', 'aero-dallas' ),
						__( "For repairs: a description of the fault and the unit's current condition", 'aero-dallas' ),
						__( 'For supply: quantities, specifications and the required delivery date', 'aero-dallas' ),
						__( 'Photos, manuals or specification sheets, if available', 'aero-dallas' ),
					)
				) .
				$h2( __( 'Our commitment', 'aero-dallas' ), 'commitment' ) .
				$p( array( $commitment ) ),
		),
		'parts-supply' => array(
			'slug'          => 'parts-equipment-supply',
			'intro'         => __( 'Supply of industrial and aviation-related parts and equipment for maintenance, repairs and operational support, sourced to your specifications.', 'aero-dallas' ),
			'scope'         => array(
				__( 'Industrial spare parts and components', 'aero-dallas' ),
				__( 'Aviation-related parts and equipment', 'aero-dallas' ),
				__( 'Ground support equipment supply', 'aero-dallas' ),
				__( 'Parts for maintenance and repair work', 'aero-dallas' ),
				__( 'Sourcing to client specifications', 'aero-dallas' ),
			),
			'hero_photo'    => 'eq-roller-conveyor',
			'gallery_photo' => 'parts-detail',
			'legacy'        => $parts_overview,
			'content'       =>
				$h2( __( 'Overview', 'aero-dallas' ), 'overview' ) .
				$p( array( $parts_overview ) ) .
				$h2( __( 'What we can source', 'aero-dallas' ), 'source' ) .
				$ul(
					array(
						__( 'Industrial spare parts for machinery and mechanical equipment', 'aero-dallas' ),
						__( 'Aviation-related parts and equipment', 'aero-dallas' ),
						__( 'Ground support equipment, including ground power units, hydraulic power units, air starter units, aircraft jacks and aircraft tow bars, subject to availability and specifications', 'aero-dallas' ),
					)
				) .
				$h2( __( 'How sourcing works', 'aero-dallas' ), 'how-it-works' ) .
				$ol(
					array(
						__( 'You share your requirements: part numbers, specifications, quantities and the equipment the parts are for.', 'aero-dallas' ),
						__( 'We identify suitable parts or equipment based on your specifications and operational requirements.', 'aero-dallas' ),
						__( 'You receive a quotation outlining scope, pricing, availability and applicable terms, including any taxes, shipping or transportation charges.', 'aero-dallas' ),
						__( 'Supply proceeds once you approve the quotation and agreed terms.', 'aero-dallas' ),
						__( 'Where applicable, supplied equipment is reviewed before handover.', 'aero-dallas' ),
					)
				) .
				$h2( __( 'What to include in your request', 'aero-dallas' ), 'request' ) .
				$ul(
					array(
						__( 'Part numbers, manufacturer and model, where known', 'aero-dallas' ),
						__( 'Quantities required', 'aero-dallas' ),
						__( 'Technical specifications, drawings or photos', 'aero-dallas' ),
						__( 'Delivery location and required date', 'aero-dallas' ),
					)
				) .
				$h2( __( 'Availability and warranty', 'aero-dallas' ), 'warranty' ) .
				$p( array( __( 'Availability depends on the part or equipment and its supplier, and is confirmed in the quotation. Warranty coverage, where applicable, is communicated at the point of sale and may vary by equipment, manufacturer or supplier.', 'aero-dallas' ) ) ),
		),
		'logistics'    => array(
			'slug'          => 'air-ground-logistics',
			'intro'         => __( 'Air and ground logistics support to move and coordinate industrial equipment, parts and operational supplies, planned around your project requirements.', 'aero-dallas' ),
			'scope'         => array(
				__( 'Air logistics coordination', 'aero-dallas' ),
				__( 'Ground transport coordination', 'aero-dallas' ),
				__( 'Movement of industrial equipment', 'aero-dallas' ),
				__( 'Parts and operational supplies', 'aero-dallas' ),
				__( 'Coordination based on project requirements', 'aero-dallas' ),
			),
			'hero_photo'    => 'logistics-freighter',
			'gallery_photo' => 'service-logistics',
			'legacy'        => $logistics_overview,
			'content'       =>
				$h2( __( 'Overview', 'aero-dallas' ), 'overview' ) .
				$p( array( $logistics_overview ) ) .
				$h2( __( 'Air logistics', 'aero-dallas' ), 'air' ) .
				$p( array( __( 'Air logistics support for parts, equipment and supplies that need to move by air, coordinated around your project requirements and timelines.', 'aero-dallas' ) ) ) .
				$h2( __( 'Ground logistics', 'aero-dallas' ), 'ground' ) .
				$p( array( __( 'Ground logistics support for moving industrial equipment, parts and operational supplies by road, coordinated to fit your operational schedule.', 'aero-dallas' ) ) ) .
				$h2( __( 'Working alongside our other services', 'aero-dallas' ), 'combined' ) .
				$p( array( __( 'Logistics can be arranged on its own or together with equipment supply, repairs and aviation ground support, so that one team coordinates the work from quotation to handover.', 'aero-dallas' ) ) ) .
				$h2( __( 'What to include in your request', 'aero-dallas' ), 'request' ) .
				$ul(
					array(
						__( 'What needs to move: description, quantity, dimensions and weight', 'aero-dallas' ),
						__( 'Collection and delivery locations', 'aero-dallas' ),
						__( 'Required collection and delivery dates', 'aero-dallas' ),
						__( 'Any special handling requirements', 'aero-dallas' ),
						__( 'Whether you also need equipment supply or repairs', 'aero-dallas' ),
					)
				) .
				$h2( __( 'Schedules and timelines', 'aero-dallas' ), 'timelines' ) .
				$p( array( __( 'Service timelines are agreed before work begins. Schedules can depend on equipment availability, parts sourcing, logistics and technical requirements, and you will be informed of significant changes to agreed timelines.', 'aero-dallas' ) ) ),
		),
	);
}

/**
 * Equipment shown in the equipment galleries, each linked to the quote form
 * with the service and equipment filled in. "fit" is "contain" for product
 * photos on a white background, so the whole item stays in view. "featured"
 * items appear on the home page; "hero" gives the order in the home hero
 * showcase (0: not shown there).
 *
 * @return array<int,array{label:string,photo:string,service:string,fit:string,featured:bool,hero:int}>
 */
function aerodallas_equipment() {
	$item = static function ( $label, $photo, $service, $fit = 'cover', $featured = false, $hero = 0 ) {
		return compact( 'label', 'photo', 'service', 'fit', 'featured', 'hero' );
	};
	return array(
		$item( __( 'Towbarless Pushback Tractors', 'aero-dallas' ), 'eq-towbarless-tractor', 'aviation', 'contain', true, 2 ),
		$item( __( 'Air Start Units (ASU)', 'aero-dallas' ), 'eq-air-start-unit', 'aviation', 'cover', false, 1 ),
		$item( __( 'Ground Power Units (GPU)', 'aero-dallas' ), 'eq-ground-power-unit', 'aviation' ),
		$item( __( 'Aircraft Jacks', 'aero-dallas' ), 'eq-aircraft-jack', 'aviation', 'cover', true, 3 ),
		$item( __( 'Aircraft Tow Bars', 'aero-dallas' ), 'eq-tow-bar', 'aviation' ),
		$item( __( 'Baggage Carousels & Belt Conveyors', 'aero-dallas' ), 'eq-baggage-carousel', 'aviation', 'cover', false, 5 ),
		$item( __( 'Incline Belt Conveyors', 'aero-dallas' ), 'eq-incline-conveyor', 'industrial', 'contain', true, 4 ),
		$item( __( 'Paging & Feeder Conveyors', 'aero-dallas' ), 'eq-paging-conveyor', 'industrial', 'contain', true, 6 ),
		$item( __( 'Roller Conveyor Systems', 'aero-dallas' ), 'eq-roller-conveyor', 'industrial' ),
		$item( __( 'CNC Milling Machines', 'aero-dallas' ), 'eq-cnc-milling', 'industrial' ),
		$item( __( 'CNC Lathes & Machine Tools', 'aero-dallas' ), 'eq-cnc-lathe', 'industrial' ),
	);
}
