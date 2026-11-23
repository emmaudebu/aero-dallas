<?php
/**
 * Contact details list. Empty Customizer fields are skipped entirely.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_rows = array();

if ( aerodallas_detail( 'phone' ) ) {
	$aerodallas_rows[] = array(
		'icon'  => 'phone',
		'label' => __( 'Phone', 'aero-dallas' ),
		'html'  => sprintf( '<a href="%1$s">%2$s</a>', esc_url( aerodallas_phone_href() ), esc_html( aerodallas_detail( 'phone' ) ) ),
	);
}
$aerodallas_email = sanitize_email( aerodallas_detail( 'email' ) );
if ( $aerodallas_email ) {
	$aerodallas_rows[] = array(
		'icon'  => 'mail',
		'label' => __( 'Email', 'aero-dallas' ),
		'html'  => sprintf( '<a href="%1$s">%2$s</a>', esc_url( 'mailto:' . $aerodallas_email ), esc_html( $aerodallas_email ) ),
	);
}
if ( aerodallas_detail( 'instagram_url' ) ) {
	$aerodallas_rows[] = array(
		'icon'  => 'instagram',
		'label' => __( 'Instagram', 'aero-dallas' ),
		'html'  => sprintf(
			'<a href="%1$s" target="_blank" rel="noopener">%2$s<span class="screen-reader-text"> %3$s</span></a>',
			esc_url( aerodallas_detail( 'instagram_url' ) ),
			esc_html( aerodallas_instagram_handle() ),
			esc_html__( '(opens in a new tab)', 'aero-dallas' )
		),
	);
}
if ( aerodallas_detail( 'address' ) ) {
	$aerodallas_rows[] = array(
		'icon'  => 'pin',
		'label' => __( 'Address', 'aero-dallas' ),
		'html'  => '<address>' . nl2br( esc_html( aerodallas_detail( 'address' ) ) ) . '</address>',
	);
}
if ( aerodallas_detail( 'hours' ) ) {
	$aerodallas_rows[] = array(
		'icon'  => 'clock',
		'label' => __( 'Business hours', 'aero-dallas' ),
		'html'  => nl2br( esc_html( aerodallas_detail( 'hours' ) ) ),
	);
}
if ( aerodallas_detail( 'regions' ) ) {
	$aerodallas_rows[] = array(
		'icon'  => 'globe',
		'label' => __( 'Regions served', 'aero-dallas' ),
		'html'  => esc_html( aerodallas_detail( 'regions' ) ),
	);
}

if ( ! $aerodallas_rows ) {
	return;
}
?>
<div class="contact-card">
	<p class="contact-card__name">AERO DALLAS INDUSTRIAL</p>
	<p class="contact-card__tag"><?php esc_html_e( 'Industrial & Aviation Support', 'aero-dallas' ); ?></p>
	<dl class="contact-list">
		<?php foreach ( $aerodallas_rows as $aerodallas_row ) : ?>
			<div class="contact-list__row">
				<dt><?php echo aerodallas_icon( $aerodallas_row['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $aerodallas_row['label'] ); ?></dt>
				<dd><?php echo $aerodallas_row['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped when built. ?></dd>
			</div>
		<?php endforeach; ?>
	</dl>
</div>
