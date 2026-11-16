<?php
/**
 * Site header: skip link, sticky header (transparent over the home hero),
 * light/dark toggle and mobile menu.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_phone      = aerodallas_detail( 'phone' );
$aerodallas_phone_href = aerodallas_phone_href();
$aerodallas_toggle     = (bool) get_theme_mod( 'aerodallas_show_toggle', true );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'aero-dallas' ); ?></a>

<div class="header-sentinel" aria-hidden="true"></div>
<header class="site-header<?php echo is_front_page() ? ' site-header--overlay' : ''; ?>" id="site-header">
	<div class="container site-header__inner">
		<?php get_template_part( 'template-parts/brand' ); ?>

		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'aero-dallas' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'primary-nav__list',
					'depth'          => 1,
					'fallback_cb'    => 'aerodallas_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="site-header__actions">
			<?php if ( $aerodallas_toggle ) : ?>
				<button class="mode-toggle" type="button" aria-pressed="false" data-mode-toggle>
					<?php echo aerodallas_icon( 'moon', 'mode-toggle__moon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo aerodallas_icon( 'sun', 'mode-toggle__sun' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Dark mode', 'aero-dallas' ); ?></span>
				</button>
			<?php endif; ?>
			<a class="btn btn--primary btn--sm header-quote" href="<?php echo esc_url( aerodallas_quote_url() ); ?>"><?php esc_html_e( 'Get a Quote', 'aero-dallas' ); ?></a>
			<button class="menu-toggle" type="button" aria-controls="mobile-menu" aria-expanded="false">
				<?php echo aerodallas_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'aero-dallas' ); ?></span>
			</button>
		</div>
	</div>
</header>

<div class="mobile-menu" id="mobile-menu" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'aero-dallas' ); ?>" hidden>
	<div class="mobile-menu__top">
		<?php get_template_part( 'template-parts/brand', null, array( 'variant' => 'white' ) ); ?>
		<button class="mobile-menu__close" type="button">
			<?php echo aerodallas_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'aero-dallas' ); ?></span>
		</button>
	</div>
	<nav class="mobile-menu__nav" aria-label="<?php esc_attr_e( 'Primary', 'aero-dallas' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mobile-menu__list',
				'depth'          => 1,
				'fallback_cb'    => 'aerodallas_menu_fallback',
			)
		);
		?>
	</nav>
	<div class="mobile-menu__actions">
		<a class="btn btn--primary btn--block" href="<?php echo esc_url( aerodallas_quote_url() ); ?>"><?php esc_html_e( 'Get a Quote', 'aero-dallas' ); ?></a>
		<?php if ( $aerodallas_phone ) : ?>
			<a class="btn btn--ghost-light btn--block" href="<?php echo esc_url( $aerodallas_phone_href ); ?>">
				<?php echo aerodallas_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php
				/* translators: %s: phone number. */
				echo esc_html( sprintf( __( 'Call %s', 'aero-dallas' ), $aerodallas_phone ) );
				?>
			</a>
		<?php endif; ?>
	</div>
</div>

<main id="main" class="site-main" tabindex="-1">
