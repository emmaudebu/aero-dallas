<?php
/**
 * Decorative routes behind a hero's content: an aircraft on a dotted air
 * route and a truck on a dashed ground route, for the air and ground sides of
 * the business. main.js starts them (not when the visitor prefers reduced
 * motion) and pauses them while off screen.
 *
 * Args:
 * - class (string) extra class on the SVG
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_route  = wp_unique_id( 'flight-route-' );
$aerodallas_ground = wp_unique_id( 'ground-route-' );
?>
<svg class="<?php echo esc_attr( trim( 'flight-path ' . ( $args['class'] ?? '' ) ) ); ?>" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
	<path id="<?php echo esc_attr( $aerodallas_route ); ?>" class="flight-path__route" d="M-80 820C260 860 470 610 720 560S1120 360 1520 90"/>
	<path class="flight-path__route flight-path__route--faint" d="M-80 300C240 210 520 330 760 250S1180 40 1520 -40"/>
	<path id="<?php echo esc_attr( $aerodallas_ground ); ?>" class="flight-path__route flight-path__route--ground" d="M-80 662C260 626 560 688 860 656S1260 614 1520 638"/>
	<g class="flight-path__plane">
		<g transform="rotate(90) scale(1.6) translate(-12 -12)">
			<?php echo strip_tags( aerodallas_icon( 'plane' ), '<path>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static icon path. ?>
		</g>
		<animateMotion dur="16s" begin="indefinite" repeatCount="indefinite" rotate="auto" keyPoints="0;1" keyTimes="0;1" calcMode="linear">
			<mpath href="#<?php echo esc_attr( $aerodallas_route ); ?>" xlink:href="#<?php echo esc_attr( $aerodallas_route ); ?>"/>
		</animateMotion>
	</g>
	<g class="flight-path__truck">
		<g transform="scale(2.1) translate(-12 -19.6)">
			<?php echo strip_tags( aerodallas_icon( 'truck' ), '<path><circle>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static icon paths. ?>
		</g>
		<animateMotion dur="26s" begin="indefinite" repeatCount="indefinite" rotate="auto" keyPoints="0;1" keyTimes="0;1" calcMode="linear">
			<mpath href="#<?php echo esc_attr( $aerodallas_ground ); ?>" xlink:href="#<?php echo esc_attr( $aerodallas_ground ); ?>"/>
		</animateMotion>
	</g>
</svg>
