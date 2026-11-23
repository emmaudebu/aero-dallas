<?php
/**
 * Statistics band. Renders only when figures are filled in under
 * Customize → AERO DALLAS INDUSTRIAL — Company Details → Home: statistics and insights.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_stats = aerodallas_stats();
if ( ! $aerodallas_stats ) {
	return;
}
$aerodallas_heading = trim( (string) get_theme_mod( 'aerodallas_stats_heading', '' ) );
?>
<section class="stats" aria-label="<?php echo esc_attr( $aerodallas_heading ? $aerodallas_heading : __( 'Key figures', 'aero-dallas' ) ); ?>">
	<div class="container">
		<?php if ( $aerodallas_heading ) : ?>
			<h2 class="stats__heading"><?php echo esc_html( $aerodallas_heading ); ?></h2>
		<?php endif; ?>
		<dl class="stats__list">
			<?php foreach ( $aerodallas_stats as $aerodallas_stat ) : ?>
				<div class="stats__item" data-reveal>
					<dt><?php echo esc_html( $aerodallas_stat['label'] ); ?></dt>
					<dd><?php echo esc_html( $aerodallas_stat['value'] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>
