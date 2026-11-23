<?php
/**
 * HTML email sent to the team when a quote request arrives.
 * Tables and inline styles so it renders in Gmail, Outlook and Apple Mail;
 * the <style> block only adds the mobile layout.
 *
 * Args (from aerodallas_quote_send_email()):
 * - post_id    (int)    request ID
 * - values     (array)  sanitised form values
 * - service    (string) service label
 * - file       (array)  name, url, size, type, attached; empty when no file
 * - file_error (string) why an uploaded file could not be saved
 * - edit_url   (string) request in WordPress
 * - source     (string) page the form was sent from
 * - received   (int)    timestamp
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_v       = $args['values'];
$aerodallas_labels  = aerodallas_quote_meta_labels();
$aerodallas_file    = $args['file'];
$aerodallas_first   = strtok( $aerodallas_v['name'], ' ' );
$aerodallas_phone   = preg_replace( '/[^\d+]/', '', $aerodallas_v['phone'] );
$aerodallas_reply   = 'mailto:' . $aerodallas_v['email'] . '?subject=' . rawurlencode(
	/* translators: %s: service. */
	sprintf( __( 'Your quote request: %s', 'aero-dallas' ), $args['service'] )
);
$aerodallas_when    = sprintf(
	/* translators: 1: date, 2: time, 3: time zone. */
	__( '%1$s at %2$s %3$s', 'aero-dallas' ),
	wp_date( get_option( 'date_format' ), $args['received'] ),
	wp_date( get_option( 'time_format' ), $args['received'] ),
	aerodallas_cs_zone_label( $args['received'] )
);
$aerodallas_font    = "font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;";
$aerodallas_display = "font-family:'Arial Black',Arial,'Helvetica Neue',Helvetica,sans-serif;";
$aerodallas_label   = $aerodallas_font . 'font-size:11px;line-height:16px;font-weight:bold;letter-spacing:1.2px;text-transform:uppercase;color:#5d687b;';
$aerodallas_value   = $aerodallas_font . 'font-size:15px;line-height:22px;font-weight:bold;color:#152d63;';
$aerodallas_link    = 'color:#152d63;text-decoration:underline;text-decoration-color:#fe7620;';
$aerodallas_heading = $aerodallas_font . 'font-size:12px;line-height:16px;font-weight:bold;letter-spacing:1.6px;text-transform:uppercase;color:#b84a06;';

/**
 * One label/value cell of the details grid.
 */
$aerodallas_cell = static function ( $label, $value_html ) use ( $aerodallas_label, $aerodallas_value ) {
	?>
	<td class="stack" width="50%" valign="top" style="padding:0 16px 22px 0;">
		<div style="<?php echo esc_attr( $aerodallas_label ); ?>"><?php echo esc_html( $label ); ?></div>
		<div style="<?php echo esc_attr( $aerodallas_value ); ?>padding-top:4px;word-break:break-word;"><?php echo $value_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the caller. ?></div>
	</td>
	<?php
};
$aerodallas_none = '<span style="color:#7d879a;font-weight:normal;">' . esc_html__( 'Not provided', 'aero-dallas' ) . '</span>';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="x-apple-disable-message-reformatting">
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<title><?php esc_html_e( 'New quote request', 'aero-dallas' ); ?></title>
<style>
	body { margin: 0; padding: 0; width: 100% !important; -webkit-text-size-adjust: 100%; }
	a { color: #152d63; }
	@media only screen and (max-width: 620px) {
		.wrap { padding: 0 !important; }
		.card { border-radius: 0 !important; }
		.pad { padding-left: 24px !important; padding-right: 24px !important; }
		.stack { display: block !important; width: 100% !important; }
		.hero-title { font-size: 24px !important; line-height: 30px !important; }
		.btn-cell { display: block !important; width: 100% !important; padding: 0 0 10px 0 !important; }
		.btn-cell a { display: block !important; text-align: center !important; }
		.hide-sm { display: none !important; }
	}
</style>
</head>
<body style="margin:0;padding:0;background-color:#eef1f6;">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;mso-hide:all;">
	<?php
	echo esc_html(
		sprintf(
			/* translators: 1: name, 2: service, 3: date and time. */
			__( '%1$s requested a quote for %2$s on %3$s.', 'aero-dallas' ),
			$aerodallas_v['name'],
			$args['service'],
			$aerodallas_when
		)
	);
	?>
	&#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;
</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#eef1f6;">
	<tr>
		<td class="wrap" align="center" style="padding:32px 16px;">
			<table role="presentation" class="card" width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px;background-color:#ffffff;border-radius:16px;overflow:hidden;">

				<!-- Header -->
				<tr>
					<td style="background-color:#fe7620;height:5px;line-height:5px;font-size:0;">&nbsp;</td>
				</tr>
				<tr>
					<td class="pad" style="background-color:#0a1430;padding:22px 40px;">
						<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td valign="middle">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration:none;">
										<img src="<?php echo esc_url( AERODALLAS_URI . '/assets/img/brand/logo-main-white.png' ); ?>" width="150" height="50" alt="AERO DALLAS INDUSTRIAL" style="display:block;width:150px;height:auto;border:0;<?php echo esc_attr( $aerodallas_display ); ?>font-size:20px;line-height:24px;color:#ffffff;">
									</a>
								</td>
								<td class="hide-sm" valign="middle" align="right" style="<?php echo esc_attr( $aerodallas_font ); ?>font-size:12px;line-height:18px;color:rgba(236,240,248,0.72);">
									<?php
									/* translators: %d: request number. */
									echo esc_html( sprintf( __( 'Request #%d', 'aero-dallas' ), $args['post_id'] ) );
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<!-- Hero -->
				<tr>
					<td class="pad" style="background-color:#152d63;background-image:linear-gradient(135deg,#152d63 0%,#0a1430 100%);padding:36px 40px 38px;">
						<table role="presentation" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td style="background-color:rgba(254,118,32,0.16);border:1px solid rgba(254,118,32,0.5);border-radius:999px;padding:6px 14px;<?php echo esc_attr( $aerodallas_font ); ?>font-size:11px;line-height:14px;font-weight:bold;letter-spacing:1.6px;text-transform:uppercase;color:#ff8b40;">
									&#9679;&nbsp; <?php esc_html_e( 'New quote request', 'aero-dallas' ); ?>
								</td>
							</tr>
						</table>
						<h1 class="hero-title" style="margin:18px 0 0;<?php echo esc_attr( $aerodallas_display ); ?>font-size:28px;line-height:34px;font-weight:900;color:#ffffff;">
							<?php echo esc_html( $aerodallas_v['name'] ); ?>
							<span style="color:#fe7620;">&middot;</span>
							<?php echo esc_html( $args['service'] ); ?>
						</h1>
						<p style="margin:10px 0 0;<?php echo esc_attr( $aerodallas_font ); ?>font-size:14px;line-height:21px;color:rgba(236,240,248,0.82);">
							<?php
							echo esc_html(
								'' !== $aerodallas_v['company']
									/* translators: 1: company, 2: date and time. */
									? sprintf( __( 'From %1$s · Received %2$s', 'aero-dallas' ), $aerodallas_v['company'], $aerodallas_when )
									/* translators: %s: date and time. */
									: sprintf( __( 'Received %s', 'aero-dallas' ), $aerodallas_when )
							);
							?>
						</p>

						<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-top:26px;">
							<tr>
								<?php if ( is_email( $aerodallas_v['email'] ) ) : ?>
									<td class="btn-cell" style="padding:0 10px 0 0;">
										<a href="<?php echo esc_url( $aerodallas_reply ); ?>" style="display:inline-block;background-color:#fe7620;border-radius:999px;padding:13px 24px;<?php echo esc_attr( $aerodallas_font ); ?>font-size:14px;line-height:18px;font-weight:bold;color:#152d63;text-decoration:none;">
											<?php
											/* translators: %s: first name. */
											echo esc_html( sprintf( __( 'Reply to %s', 'aero-dallas' ), $aerodallas_first ) );
											?>
										</a>
									</td>
								<?php endif; ?>
								<td class="btn-cell" style="padding:0;">
									<a href="<?php echo esc_url( 'tel:' . $aerodallas_phone ); ?>" style="display:inline-block;border:1px solid rgba(255,255,255,0.45);border-radius:999px;padding:12px 23px;<?php echo esc_attr( $aerodallas_font ); ?>font-size:14px;line-height:18px;font-weight:bold;color:#ffffff;text-decoration:none;">
										<?php
										/* translators: %s: phone number. */
										echo esc_html( sprintf( __( 'Call %s', 'aero-dallas' ), $aerodallas_v['phone'] ) );
										?>
									</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<!-- Contact details -->
				<tr>
					<td class="pad" style="padding:34px 40px 12px;">
						<div style="<?php echo esc_attr( $aerodallas_heading ); ?>padding-bottom:18px;"><?php esc_html_e( 'Contact details', 'aero-dallas' ); ?></div>
						<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<?php
								$aerodallas_cell( $aerodallas_labels['name'], esc_html( $aerodallas_v['name'] ) );
								$aerodallas_cell( $aerodallas_labels['company'], '' === $aerodallas_v['company'] ? $aerodallas_none : esc_html( $aerodallas_v['company'] ) );
								?>
							</tr>
							<tr>
								<?php
								$aerodallas_cell( $aerodallas_labels['email'], sprintf( '<a href="%1$s" style="%2$s">%3$s</a>', esc_url( 'mailto:' . $aerodallas_v['email'] ), esc_attr( $aerodallas_link ), esc_html( $aerodallas_v['email'] ) ) );
								$aerodallas_cell( $aerodallas_labels['phone'], sprintf( '<a href="%1$s" style="%2$s">%3$s</a>', esc_url( 'tel:' . $aerodallas_phone ), esc_attr( $aerodallas_link ), esc_html( $aerodallas_v['phone'] ) ) );
								?>
							</tr>
						</table>
					</td>
				</tr>

				<!-- Request -->
				<tr>
					<td class="pad" style="padding:0 40px;">
						<div style="border-top:1px solid #e3e8f0;padding-top:28px;">
							<div style="<?php echo esc_attr( $aerodallas_heading ); ?>padding-bottom:18px;"><?php esc_html_e( 'The request', 'aero-dallas' ); ?></div>
							<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<?php
									$aerodallas_cell(
										$aerodallas_labels['service'],
										sprintf(
											'<span style="display:inline-block;background-color:#fff1e7;border-radius:999px;padding:4px 12px;color:#b84a06;font-size:14px;">%s</span>',
											esc_html( $args['service'] )
										)
									);
									$aerodallas_cell( $aerodallas_labels['equipment'], '' === $aerodallas_v['equipment'] ? $aerodallas_none : esc_html( $aerodallas_v['equipment'] ) );
									?>
								</tr>
							</table>
							<div style="<?php echo esc_attr( $aerodallas_label ); ?>padding-bottom:8px;"><?php echo esc_html( $aerodallas_labels['description'] ); ?></div>
							<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td style="background-color:#f4f6fa;border-left:4px solid #fe7620;border-radius:0 10px 10px 0;padding:18px 20px;<?php echo esc_attr( $aerodallas_font ); ?>font-size:15px;line-height:24px;color:#445066;word-break:break-word;">
										<?php echo nl2br( esc_html( $aerodallas_v['description'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped first. ?>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>

				<?php if ( $aerodallas_file || $args['file_error'] ) : ?>
					<!-- Specifications file -->
					<tr>
						<td class="pad" style="padding:26px 40px 0;">
							<div style="<?php echo esc_attr( $aerodallas_label ); ?>padding-bottom:8px;"><?php esc_html_e( 'Specifications file', 'aero-dallas' ); ?></div>
							<?php if ( $aerodallas_file ) : ?>
								<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e3e8f0;border-radius:12px;">
									<tr>
										<td width="52" valign="middle" style="padding:14px 0 14px 16px;">
											<div style="width:44px;height:44px;line-height:44px;border-radius:10px;background-color:#152d63;text-align:center;<?php echo esc_attr( $aerodallas_font ); ?>font-size:11px;font-weight:bold;letter-spacing:.5px;color:#ffffff;"><?php echo esc_html( $aerodallas_file['type'] ); ?></div>
										</td>
										<td valign="middle" style="padding:14px 12px;<?php echo esc_attr( $aerodallas_font ); ?>">
											<div style="font-size:14px;line-height:20px;font-weight:bold;color:#152d63;word-break:break-all;"><?php echo esc_html( $aerodallas_file['name'] ); ?></div>
											<div style="font-size:12px;line-height:18px;color:#5d687b;">
												<?php
												echo esc_html(
													$aerodallas_file['attached']
														/* translators: %s: file size. */
														? sprintf( __( '%s · attached to this email', 'aero-dallas' ), $aerodallas_file['size'] )
														: $aerodallas_file['size']
												);
												?>
											</div>
										</td>
										<td valign="middle" align="right" style="padding:14px 16px 14px 0;">
											<a href="<?php echo esc_url( $aerodallas_file['url'] ); ?>" style="<?php echo esc_attr( $aerodallas_font ); ?>font-size:13px;font-weight:bold;<?php echo esc_attr( $aerodallas_link ); ?>"><?php esc_html_e( 'Download', 'aero-dallas' ); ?></a>
										</td>
									</tr>
								</table>
							<?php else : ?>
								<div style="background-color:#fdf0ef;border-radius:10px;padding:14px 16px;<?php echo esc_attr( $aerodallas_font ); ?>font-size:14px;line-height:20px;color:#7a1a12;">
									<?php
									/* translators: %s: error message. */
									echo esc_html( sprintf( __( 'The visitor chose a file, but it could not be saved: %s Ask them to email it to you.', 'aero-dallas' ), $args['file_error'] ) );
									?>
								</div>
							<?php endif; ?>
						</td>
					</tr>
				<?php endif; ?>

				<!-- Call to action -->
				<tr>
					<td class="pad" align="center" style="padding:34px 40px 38px;">
						<table role="presentation" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td style="background-color:#152d63;border-radius:999px;">
									<a href="<?php echo esc_url( $args['edit_url'] ); ?>" style="display:inline-block;padding:14px 30px;<?php echo esc_attr( $aerodallas_font ); ?>font-size:14px;line-height:18px;font-weight:bold;color:#ffffff;text-decoration:none;">
										<?php esc_html_e( 'Open request in WordPress', 'aero-dallas' ); ?> &rarr;
									</a>
								</td>
							</tr>
						</table>
						<p style="margin:14px 0 0;<?php echo esc_attr( $aerodallas_font ); ?>font-size:13px;line-height:20px;color:#5d687b;">
							<?php
							/* translators: %s: first name. */
							echo esc_html( sprintf( __( 'Reply to this email to answer %s directly.', 'aero-dallas' ), $aerodallas_first ) );
							?>
						</p>
					</td>
				</tr>

				<!-- Footer -->
				<tr>
					<td class="pad" style="background-color:#0a1430;padding:22px 40px;<?php echo esc_attr( $aerodallas_font ); ?>font-size:12px;line-height:18px;color:rgba(236,240,248,0.64);">
						<?php if ( $args['source'] ) : ?>
							<?php esc_html_e( 'Sent from the Request a Quote form on', 'aero-dallas' ); ?>
							<a href="<?php echo esc_url( $args['source'] ); ?>" style="color:#ff8b40;text-decoration:none;word-break:break-all;"><?php echo esc_html( preg_replace( '#^https?://#', '', untrailingslashit( $args['source'] ) ) ); ?></a>
						<?php else : ?>
							<?php esc_html_e( 'Sent from the Request a Quote form on your website.', 'aero-dallas' ); ?>
						<?php endif; ?>
						<br>
						<?php
						/* translators: %s: year. */
						echo esc_html( sprintf( __( '© %s AERO DALLAS INDUSTRIAL. All rights reserved.', 'aero-dallas' ), wp_date( 'Y', $args['received'] ) ) );
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
