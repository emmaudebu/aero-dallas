<?php
/**
 * Coming soon page: a complete document served by inc/coming-soon.php.
 * Heading and text, a live countdown to the launch date, contact links and a
 * photo, with the shared flight path flying across the page.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_launch    = aerodallas_cs_launch();
$aerodallas_remaining = $aerodallas_launch ? max( 0, $aerodallas_launch->getTimestamp() - time() ) : 0;
$aerodallas_toggle    = (bool) get_theme_mod( 'aerodallas_show_toggle', true );
$aerodallas_phone     = aerodallas_detail( 'phone' );
$aerodallas_email     = aerodallas_detail( 'email' );
$aerodallas_instagram = aerodallas_detail( 'instagram_url' );
$aerodallas_services  = array_slice( aerodallas_items( 'aerodallas_service' ), 0, 4 );
$aerodallas_units     = array(
	'days'    => array( __( 'Days', 'aero-dallas' ), intdiv( $aerodallas_remaining, DAY_IN_SECONDS ) ),
	'hours'   => array( __( 'Hours', 'aero-dallas' ), intdiv( $aerodallas_remaining % DAY_IN_SECONDS, HOUR_IN_SECONDS ) ),
	'minutes' => array( __( 'Minutes', 'aero-dallas' ), intdiv( $aerodallas_remaining % HOUR_IN_SECONDS, MINUTE_IN_SECONDS ) ),
	'seconds' => array( __( 'Seconds', 'aero-dallas' ), $aerodallas_remaining % MINUTE_IN_SECONDS ),
);

if ( $aerodallas_launch ) {
	$aerodallas_when = sprintf(
		/* translators: 1: date, 2: time, 3: time zone. */
		__( '%1$s at %2$s %3$s', 'aero-dallas' ),
		wp_date( get_option( 'date_format' ), $aerodallas_launch->getTimestamp() ),
		wp_date( get_option( 'time_format' ), $aerodallas_launch->getTimestamp() ),
		aerodallas_cs_zone_label( $aerodallas_launch->getTimestamp() )
	);
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="cs">
	<div class="cs__sky" aria-hidden="true">
		<span class="cs__glow cs__glow--one"></span>
		<span class="cs__glow cs__glow--two"></span>
		<?php get_template_part( 'template-parts/flight-path' ); ?>
	</div>

	<header class="cs__top">
		<?php get_template_part( 'template-parts/brand' ); ?>
		<?php if ( $aerodallas_toggle ) : ?>
			<button class="mode-toggle" type="button" aria-pressed="false" data-mode-toggle>
				<?php echo aerodallas_icon( 'moon', 'mode-toggle__moon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo aerodallas_icon( 'sun', 'mode-toggle__sun' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Dark mode', 'aero-dallas' ); ?></span>
			</button>
		<?php endif; ?>
	</header>

	<main class="cs__main" id="main">
		<div class="cs__content">
			<?php if ( aerodallas_cs( 'cs_eyebrow' ) ) : ?>
				<p class="cs__badge cs-rise"><span class="cs__pulse" aria-hidden="true"></span><?php echo esc_html( aerodallas_cs( 'cs_eyebrow' ) ); ?></p>
			<?php endif; ?>
			<h1 class="cs__title cs-rise"><?php echo aerodallas_highlight( aerodallas_cs( 'cs_heading' ), aerodallas_cs( 'cs_highlight' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
			<?php if ( aerodallas_cs( 'cs_text' ) ) : ?>
				<p class="cs__lead cs-rise"><?php echo esc_html( aerodallas_cs( 'cs_text' ) ); ?></p>
			<?php endif; ?>

			<?php if ( $aerodallas_launch ) : ?>
				<div class="cs-count cs-rise<?php echo $aerodallas_remaining ? '' : ' is-done'; ?>" data-launch="<?php echo esc_attr( (string) ( $aerodallas_launch->getTimestamp() * 1000 ) ); ?>" data-now="<?php echo esc_attr( (string) ( time() * 1000 ) ); ?>" data-reload="<?php echo get_theme_mod( 'aerodallas_cs_auto_open', true ) ? '1' : '0'; ?>">
					<div class="cs-count__timer" role="timer" aria-label="<?php esc_attr_e( 'Time until launch', 'aero-dallas' ); ?>">
						<?php foreach ( $aerodallas_units as $aerodallas_unit => $aerodallas_part ) : ?>
							<div class="cs-count__unit">
								<span class="cs-count__num" data-unit="<?php echo esc_attr( $aerodallas_unit ); ?>"><?php echo esc_html( str_pad( (string) $aerodallas_part[1], 2, '0', STR_PAD_LEFT ) ); ?></span>
								<span class="cs-count__label"><?php echo esc_html( $aerodallas_part[0] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
					<p class="cs-count__when">
						<?php echo aerodallas_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span class="cs-count__soon"><?php esc_html_e( 'Launching on', 'aero-dallas' ); ?> <time datetime="<?php echo esc_attr( $aerodallas_launch->format( DATE_W3C ) ); ?>"><?php echo esc_html( $aerodallas_when ); ?></time></span>
						<span class="cs-count__now"><?php esc_html_e( 'Final checks complete. We are launching any moment now.', 'aero-dallas' ); ?></span>
					</p>
				</div>
			<?php endif; ?>

			<?php if ( $aerodallas_phone || $aerodallas_email || $aerodallas_instagram ) : ?>
				<div class="cs__actions cs-rise">
					<?php if ( $aerodallas_phone ) : ?>
						<a class="btn btn--primary btn--lg" href="<?php echo esc_url( aerodallas_phone_href() ); ?>"><?php echo aerodallas_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $aerodallas_phone ); ?></a>
					<?php endif; ?>
					<?php if ( $aerodallas_email ) : ?>
						<a class="btn btn--outline btn--lg" href="<?php echo esc_url( 'mailto:' . $aerodallas_email ); ?>"><?php echo aerodallas_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Email Our Team', 'aero-dallas' ); ?></a>
					<?php endif; ?>
					<?php if ( $aerodallas_instagram ) : ?>
						<a class="cs__social" href="<?php echo esc_url( $aerodallas_instagram ); ?>" target="_blank" rel="noopener">
							<?php echo aerodallas_icon( 'instagram' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="screen-reader-text"><?php esc_html_e( 'AERO DALLAS on Instagram (opens in a new tab)', 'aero-dallas' ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="cs__visual cs-rise">
			<figure class="cs-photo">
				<?php echo aerodallas_image( (int) get_theme_mod( 'aerodallas_cs_image', 0 ), 'hero-jet', array( 'class' => 'cs-photo__img', 'sizes' => '(min-width: 1000px) 46vw, 100vw', 'eager' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<figcaption class="cs-status">
					<span class="cs-status__radar" aria-hidden="true"><?php echo aerodallas_icon( 'plane' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span>
						<span class="cs-status__label"><?php esc_html_e( 'Flight status', 'aero-dallas' ); ?></span>
						<span class="cs-status__value"><?php esc_html_e( 'Preparing for take-off', 'aero-dallas' ); ?></span>
					</span>
				</figcaption>
			</figure>
			<?php if ( $aerodallas_services ) : ?>
				<ul class="cs-services" aria-label="<?php esc_attr_e( 'Our services', 'aero-dallas' ); ?>">
					<?php foreach ( $aerodallas_services as $aerodallas_i => $aerodallas_service ) : ?>
						<li class="cs-services__item">
							<span class="cs-services__icon"><?php echo aerodallas_icon( aerodallas_item_icon( $aerodallas_service->ID, $aerodallas_i, array( 'gear', 'plane', 'parts', 'truck' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php echo esc_html( get_the_title( $aerodallas_service ) ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</main>

	<footer class="cs__foot">
		<p>
			<?php
			/* translators: %s: year. */
			echo esc_html( sprintf( __( '© %s AERO DALLAS. All rights reserved.', 'aero-dallas' ), wp_date( 'Y' ) ) );
			?>
		</p>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
