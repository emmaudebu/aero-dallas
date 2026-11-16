<?php
/**
 * Inline SVG icon set. 24×24 grid, 1.75 stroke, round caps and joins.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon path data keyed by name.
 *
 * @return array<string,string>
 */
function aerodallas_icon_paths() {
	return array(
		'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-up'    => '<path d="M12 19V5M6 11l6-6 6 6"/>',
		'arrow-down'  => '<path d="M12 5v14M6 13l6 6 6-6"/>',
		'phone'       => '<path d="M6.6 3.5h2.6l1.5 4-2 1.3a11 11 0 0 0 6.5 6.5l1.3-2 4 1.5v2.6a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4.6 5.7a2 2 0 0 1 2-2.2z"/>',
		'mail'        => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3.5 7 8.5 6 8.5-6"/>',
		'pin'         => '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
		'clock'       => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.2 2"/>',
		'globe'       => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.6 2.6 3.8 5.6 3.8 9s-1.2 6.4-3.8 9c-2.6-2.6-3.8-5.6-3.8-9S9.4 5.6 12 3z"/>',
		'instagram'   => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>',
		'menu'        => '<path d="M3 7h18M3 12h18M3 17h18"/>',
		'close'       => '<path d="M6 6l12 12M18 6 6 18"/>',
		'check'       => '<path d="m5 12.5 4.5 4.5L19 7"/>',
		'check-ring'  => '<circle cx="12" cy="12" r="9"/><path d="m8 12.3 2.8 2.8L16.2 9.6"/>',
		'plus'        => '<path d="M12 5v14M5 12h14"/>',
		'upload'      => '<path d="M12 15V4M7.5 8.5 12 4l4.5 4.5M4 15v3.5A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5V15"/>',
		'alert'       => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5v5.5M12 16.5h.01"/>',
		'gear'        => '<circle cx="12" cy="12" r="3.2"/><circle cx="12" cy="12" r="6.8"/><path d="M19 12h2.5M16.95 16.95l1.77 1.77M12 19v2.5M7.05 16.95l-1.77 1.77M5 12H2.5M7.05 7.05 5.28 5.28M12 5V2.5M16.95 7.05l1.77-1.77"/>',
		'plane'       => '<path d="M12 2.5c.9 0 1.5 1 1.5 2.2V9l7 4v2l-7-2v4.2l2.2 1.6v1.7L12 19.6l-3.7.9v-1.7l2.2-1.6V13l-7 2v-2l7-4V4.7c0-1.2.6-2.2 1.5-2.2z"/>',
		'parts'       => '<path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3z"/><path d="m4 7.5 8 4.5 8-4.5M12 12v9M8 5.3l8 4.5"/>',
		'truck'       => '<path d="M2.5 6h11v9.5h-11zM13.5 9.5h4.2l3 3.2v2.8h-7.2"/><circle cx="6.5" cy="17.5" r="1.8"/><circle cx="17" cy="17.5" r="1.8"/>',
		'wrench'      => '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.1l-5.8 5.8a1.8 1.8 0 0 0 2.5 2.5l5.8-5.8a4 4 0 0 0 5.1-5.4l-2.4 2.4-2.2-.6-.6-2.2 3-3z"/>',
		'shield'      => '<path d="M12 3 19 6v5.5c0 4.5-3 8-7 9.5-4-1.5-7-5-7-9.5V6l7-3z"/><path d="m8.8 12 2.2 2.2 4.4-4.4"/>',
		'briefcase'   => '<rect x="3" y="7.5" width="18" height="12" rx="1.5"/><path d="M8.5 7.5v-2a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v2M3 12.5h18M11 12.5v2h2v-2"/>',
		'target'      => '<circle cx="12" cy="12" r="7.5"/><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/>',
		'gauge'       => '<path d="M4.2 17.5a8.5 8.5 0 1 1 15.6 0"/><path d="m12 14 4-5"/><circle cx="12" cy="14" r="1.3"/><path d="M7 17.5h10"/>',
		'scale'       => '<path d="M12 4v16M8 20h8M5 7h14"/><path d="m5 7-2.5 6a2.5 2.5 0 0 0 5 0L5 7zM19 7l-2.5 6a2.5 2.5 0 0 0 5 0L19 7z"/>',
		'flag'        => '<path d="M5 21V4M5 4.5h12l-2.2 4 2.2 4H5"/>',
		'chat'        => '<path d="M4 5.5h16v10H9.5L5 19.5v-4H4z"/><path d="M8 9.5h8M8 12.5h5"/>',
		'clipboard'   => '<rect x="5" y="4.5" width="14" height="16" rx="1.5"/><path d="M9 4.5V3h6v1.5M8.5 10h7M8.5 13.5h7M8.5 17h4"/>',
		'document'    => '<path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4M9 12h6M9 15.5h6"/>',
		'handover'    => '<path d="M3 13.5h4l3 1.5h3.5a1.5 1.5 0 0 1 0 3H10M7 18.5l3.3 1h4.4L21 16a1.5 1.5 0 0 0-1.9-2.2l-4.3 2"/><path d="M9.5 4.5h7v6h-7z"/>',
		'magnifier'   => '<circle cx="10.5" cy="10.5" r="6"/><path d="m15 15 5.5 5.5"/>',
		'sun'         => '<circle cx="12" cy="12" r="4"/><path d="M12 2.5v2M12 19.5v2M2.5 12h2M19.5 12h2M5.3 5.3l1.4 1.4M17.3 17.3l1.4 1.4M5.3 18.7l1.4-1.4M17.3 6.7l1.4-1.4"/>',
		'moon'        => '<path d="M20 14.5A8 8 0 0 1 9.5 4a8 8 0 1 0 10.5 10.5z"/>',
		'quote'       => '<path d="M9.5 7.5H6a1.5 1.5 0 0 0-1.5 1.5v3A1.5 1.5 0 0 0 6 13.5h3v1a3 3 0 0 1-3 3M19.5 7.5H16a1.5 1.5 0 0 0-1.5 1.5v3a1.5 1.5 0 0 0 1.5 1.5h3v1a3 3 0 0 1-3 3"/>',
		'headset'     => '<path d="M4.5 14v-2a7.5 7.5 0 0 1 15 0v2"/><rect x="3.5" y="13.5" width="4" height="6" rx="1.2"/><rect x="16.5" y="13.5" width="4" height="6" rx="1.2"/><path d="M18.5 19.5c0 1-1 2-3 2H13"/>',
	);
}

/**
 * Return an inline SVG icon.
 *
 * @param string $name  Icon name.
 * @param string $class Extra classes.
 * @return string
 */
function aerodallas_icon( $name, $class = '' ) {
	$paths = aerodallas_icon_paths();
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg class="icon icon-%1$s %2$s" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		esc_attr( $class ),
		$paths[ $name ] // Static markup defined above.
	);
}

/**
 * Icon names offered for editable items.
 *
 * @return array<string,string> name => label.
 */
function aerodallas_icon_choices() {
	return array(
		'gear'      => __( 'Gear', 'aero-dallas' ),
		'plane'     => __( 'Aircraft', 'aero-dallas' ),
		'parts'     => __( 'Parts / box', 'aero-dallas' ),
		'truck'     => __( 'Truck', 'aero-dallas' ),
		'wrench'    => __( 'Wrench', 'aero-dallas' ),
		'shield'    => __( 'Shield', 'aero-dallas' ),
		'briefcase' => __( 'Briefcase', 'aero-dallas' ),
		'target'    => __( 'Target', 'aero-dallas' ),
		'gauge'     => __( 'Gauge', 'aero-dallas' ),
		'scale'     => __( 'Scales', 'aero-dallas' ),
		'flag'      => __( 'Flag', 'aero-dallas' ),
		'chat'      => __( 'Conversation', 'aero-dallas' ),
		'clipboard' => __( 'Clipboard', 'aero-dallas' ),
		'document'  => __( 'Document', 'aero-dallas' ),
		'handover'  => __( 'Handover', 'aero-dallas' ),
		'magnifier' => __( 'Magnifier', 'aero-dallas' ),
		'headset'   => __( 'Headset', 'aero-dallas' ),
		'check'     => __( 'Check', 'aero-dallas' ),
	);
}
