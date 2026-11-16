<?php
/**
 * Site footer and mobile action bar.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_phone   = aerodallas_detail( 'phone' );
$aerodallas_email   = sanitize_email( aerodallas_detail( 'email' ) );
$aerodallas_address = aerodallas_detail( 'address' );
$aerodallas_hours   = aerodallas_detail( 'hours' );
$aerodallas_insta   = aerodallas_detail( 'instagram_url' );
$aerodallas_policy_links = array(
	'privacy'             => __( 'Privacy Policy', 'aero-dallas' ),
	'quotation-payment'   => __( 'Quotation & Payment', 'aero-dallas' ),
	'cancellation-refund' => __( 'Cancellation & Refund', 'aero-dallas' ),
	'warranty'            => __( 'Warranty & After-Sales', 'aero-dallas' ),
	'health-safety-quality' => __( 'Health, Safety & Quality', 'aero-dallas' ),
);
?>
</main>

<footer class="site-footer">
	<div class="site-footer__swoosh" aria-hidden="true">
		<?php get_template_part( 'template-parts/swoosh' ); ?>
	</div>
	<div class="container site-footer__grid">
		<div class="site-footer__brand">
			<?php get_template_part( 'template-parts/brand', null, array( 'variant' => 'white' ) ); ?>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="site-footer__tagline"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
			<a class="btn btn--primary btn--sm" href="<?php echo esc_url( aerodallas_quote_url() ); ?>"><?php esc_html_e( 'Request a Quote', 'aero-dallas' ); ?></a>
		</div>

		<nav class="site-footer__col" aria-labelledby="footer-quick-links">
			<h2 class="site-footer__heading" id="footer-quick-links"><?php esc_html_e( 'Quick links', 'aero-dallas' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-list',
					'depth'          => 1,
					'fallback_cb'    => 'aerodallas_menu_fallback',
				)
			);
			?>
		</nav>

		<nav class="site-footer__col" aria-labelledby="footer-services">
			<h2 class="site-footer__heading" id="footer-services"><?php esc_html_e( 'Services', 'aero-dallas' ); ?></h2>
			<ul class="footer-list">
				<?php foreach ( aerodallas_items( 'aerodallas_service' ) as $aerodallas_service ) : ?>
					<li><a href="<?php echo esc_url( aerodallas_page_url( 'services', (string) get_post_meta( $aerodallas_service->ID, '_aerodallas_anchor', true ) ) ); ?>"><?php echo esc_html( get_the_title( $aerodallas_service ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<div class="site-footer__col">
			<h2 class="site-footer__heading"><?php esc_html_e( 'Contact', 'aero-dallas' ); ?></h2>
			<ul class="footer-contact">
				<?php if ( $aerodallas_phone ) : ?>
					<li><?php echo aerodallas_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="<?php echo esc_url( aerodallas_phone_href() ); ?>"><?php echo esc_html( $aerodallas_phone ); ?></a></li>
				<?php endif; ?>
				<?php if ( $aerodallas_email ) : ?>
					<li><?php echo aerodallas_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="<?php echo esc_url( 'mailto:' . $aerodallas_email ); ?>"><?php echo esc_html( $aerodallas_email ); ?></a></li>
				<?php endif; ?>
				<?php if ( $aerodallas_insta ) : ?>
					<li><?php echo aerodallas_icon( 'instagram' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="<?php echo esc_url( $aerodallas_insta ); ?>" rel="noopener" target="_blank"><?php echo esc_html( aerodallas_instagram_handle() ); ?><span class="screen-reader-text"><?php esc_html_e( '(Instagram, opens in a new tab)', 'aero-dallas' ); ?></span></a></li>
				<?php endif; ?>
				<?php if ( $aerodallas_address ) : ?>
					<li><?php echo aerodallas_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><address><?php echo nl2br( esc_html( $aerodallas_address ) ); ?></address></li>
				<?php endif; ?>
				<?php if ( $aerodallas_hours ) : ?>
					<li><?php echo aerodallas_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo nl2br( esc_html( $aerodallas_hours ) ); ?></span></li>
				<?php endif; ?>
			</ul>
		</div>

		<nav class="site-footer__col" aria-labelledby="footer-policies">
			<h2 class="site-footer__heading" id="footer-policies"><?php esc_html_e( 'Policies', 'aero-dallas' ); ?></h2>
			<ul class="footer-list">
				<?php foreach ( $aerodallas_policy_links as $aerodallas_anchor => $aerodallas_label ) : ?>
					<li><a href="<?php echo esc_url( aerodallas_page_url( 'policies', $aerodallas_anchor ) ); ?>"><?php echo esc_html( $aerodallas_label ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>
	</div>

	<div class="container site-footer__bottom">
		<p>
			<?php
			/* translators: %s: year. */
			echo esc_html( sprintf( __( '© %s AERO DALLAS. All rights reserved.', 'aero-dallas' ), wp_date( 'Y' ) ) );
			?>
		</p>
		<p><a href="<?php echo esc_url( aerodallas_page_url( 'policies', 'privacy' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'aero-dallas' ); ?></a></p>
		<p><a class="to-top" href="#main"><?php esc_html_e( 'Back to top', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-up' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></p>
	</div>
</footer>

<nav class="action-bar" aria-label="<?php esc_attr_e( 'Quick actions', 'aero-dallas' ); ?>">
	<?php if ( $aerodallas_phone ) : ?>
		<a class="action-bar__call" href="<?php echo esc_url( aerodallas_phone_href() ); ?>">
			<?php echo aerodallas_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span><?php esc_html_e( 'Call', 'aero-dallas' ); ?></span>
		</a>
	<?php endif; ?>
	<a class="action-bar__quote" href="<?php echo esc_url( aerodallas_quote_url() ); ?>">
		<?php echo aerodallas_icon( 'document' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><?php esc_html_e( 'Request a Quote', 'aero-dallas' ); ?></span>
	</a>
</nav>

<?php wp_footer(); ?>
</body>
</html>
