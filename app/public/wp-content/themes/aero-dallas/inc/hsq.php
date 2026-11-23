<?php
/**
 * Health, Safety & Quality policy: the text used in full on the Policies page
 * (section F) and as a summary section on the Home and About pages.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Health, Safety & Quality policy text.
 *
 * @return array{intro:string[],principles_intro:string,principles:array<int,array{title:string,icon:string,text:string[]}>,commitment:string}
 */
function aerodallas_hsq() {
	return array(
		'intro'            => array(
			__( 'At AERO DALLAS INDUSTRIAL, we recognise that health, safety, and quality are fundamental to the successful delivery of our industrial, mechanical engineering, aviation ground support, and logistics services.', 'aero-dallas' ),
			__( 'We are committed to maintaining safe working environments, protecting the health and wellbeing of our employees, clients, contractors, and other persons who may be affected by our operations, while ensuring that our services and products meet the required standards of quality, reliability, and performance.', 'aero-dallas' ),
		),
		'principles_intro' => __( 'Our commitment is guided by the following principles:', 'aero-dallas' ),
		'principles'       => array(
			array(
				'title' => __( 'Health & Wellbeing', 'aero-dallas' ),
				'icon'  => 'heart',
				'text'  => array(
					__( 'We strive to promote and maintain a healthy working environment by identifying potential health risks, encouraging safe working practices, and taking appropriate measures to prevent work-related injuries and occupational illness.', 'aero-dallas' ),
				),
			),
			array(
				'title' => __( 'Workplace Safety', 'aero-dallas' ),
				'icon'  => 'shield',
				'text'  => array(
					__( 'We are committed to identifying, assessing, and managing operational hazards associated with machinery, equipment, maintenance activities, aviation ground support, and logistics operations.', 'aero-dallas' ),
					__( 'We aim to ensure that appropriate safety procedures, equipment handling practices, and protective measures are followed during the execution of our services.', 'aero-dallas' ),
				),
			),
			array(
				'title' => __( 'Quality Assurance', 'aero-dallas' ),
				'icon'  => 'check-ring',
				'text'  => array(
					__( 'We are committed to delivering services and supplying equipment that meet agreed specifications, client requirements, and applicable quality standards.', 'aero-dallas' ),
					__( 'We promote accuracy, technical competence, proper inspection, and attention to detail throughout our service delivery process.', 'aero-dallas' ),
				),
			),
			array(
				'title' => __( 'Equipment Maintenance & Operational Integrity', 'aero-dallas' ),
				'icon'  => 'wrench',
				'text'  => array(
					__( 'We recognise the importance of properly maintained and functional equipment in supporting safe and efficient operations.', 'aero-dallas' ),
					__( 'Where applicable, we support appropriate inspection, maintenance, repair, and handling procedures to promote equipment reliability and operational integrity.', 'aero-dallas' ),
				),
			),
			array(
				'title' => __( 'Compliance & Responsibility', 'aero-dallas' ),
				'icon'  => 'scale',
				'text'  => array(
					__( 'We aim to conduct our operations in accordance with applicable health, safety, environmental, quality, and industry requirements.', 'aero-dallas' ),
					__( 'We expect employees, contractors, and relevant service partners to demonstrate responsibility, professionalism, and commitment to safe and quality-conscious practices.', 'aero-dallas' ),
				),
			),
			array(
				'title' => __( 'Continuous Improvement', 'aero-dallas' ),
				'icon'  => 'trend',
				'text'  => array(
					__( 'We are committed to reviewing our operational practices, identifying areas for improvement, and taking appropriate corrective actions where necessary.', 'aero-dallas' ),
					__( 'Through continuous improvement, we strive to strengthen our safety culture, enhance service quality, and maintain the confidence of our clients and business partners.', 'aero-dallas' ),
				),
			),
		),
		'commitment'       => __( 'AERO DALLAS INDUSTRIAL believes that quality service begins with safe operations. We remain committed to protecting people, maintaining professional standards, promoting equipment reliability, and delivering dependable industrial and aviation support solutions.', 'aero-dallas' ),
	);
}

/**
 * The policy as block markup for the Policies page, without its H2 (the
 * page adds "F. Health, Safety & Quality Policy" before it).
 *
 * @return string
 */
function aerodallas_hsq_blocks() {
	$hsq = aerodallas_hsq();
	$out = aerodallas_blocks_paragraphs( array_merge( $hsq['intro'], array( $hsq['principles_intro'] ) ) );
	foreach ( $hsq['principles'] as $i => $principle ) {
		$out .= '<!-- wp:heading {"level":3} -->' . "\n" . '<h3 class="wp-block-heading">' . esc_html( ( $i + 1 ) . '. ' . $principle['title'] ) . "</h3>\n<!-- /wp:heading -->\n\n";
		$out .= aerodallas_blocks_paragraphs( $principle['text'] );
	}
	$out .= '<!-- wp:heading {"level":3} -->' . "\n" . '<h3 class="wp-block-heading">' . esc_html__( 'Our Commitment', 'aero-dallas' ) . "</h3>\n<!-- /wp:heading -->\n\n";
	return $out . aerodallas_blocks_paragraphs( array( $hsq['commitment'] ) );
}

/**
 * 1.5.0: put the full Health, Safety & Quality policy on the Policies page in
 * place of the short draft paragraph. Left alone if that section was edited.
 */
function aerodallas_upgrade_hsq_policy() {
	$ids = (array) get_option( 'aerodallas_page_ids', array() );
	$id  = (int) ( $ids['policies'] ?? 0 );
	$page = $id ? get_post( $id ) : null;
	if ( ! $page ) {
		return;
	}
	// The section runs from its heading block to the next H2 heading block.
	$pattern = '/(<!-- wp:heading \{"anchor":"health-safety-quality"\} -->.*?<!-- \/wp:heading -->\s*)(.*?)(?=<!-- wp:heading \{"anchor"|$)/s';
	if ( ! preg_match( $pattern, $page->post_content, $match ) ) {
		return;
	}
	$normalise = static function ( $html ) {
		return trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( aerodallas_full_business_name( (string) $html ) ) ) );
	};
	$draft = 'AERO DALLAS INDUSTRIAL recognises the importance of safety, quality, and responsible operational practices in industrial and aviation support environments. We aim to promote safe working practices, maintain professional service standards, follow relevant equipment handling procedures, support proper equipment maintenance and functionality, and encourage compliance with applicable safety and operational requirements.';
	if ( $normalise( $match[2] ) !== $draft ) {
		return;
	}
	$content = str_replace( $match[0], $match[1] . aerodallas_hsq_blocks(), $page->post_content );

	// Saved as the theme, not as whoever loaded the page, so block markup is kept.
	$kses = false !== has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	if ( $kses ) {
		kses_remove_filters();
	}
	wp_update_post(
		wp_slash(
			array(
				'ID'           => $page->ID,
				'post_content' => $content,
			)
		)
	);
	if ( $kses ) {
		kses_init_filters();
	}
}
