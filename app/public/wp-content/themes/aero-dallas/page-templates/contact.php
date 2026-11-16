<?php
/**
 * Template Name: Contact Us
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
the_post();

ob_start();
the_content();
$aerodallas_intro = (string) ob_get_clean();

$aerodallas_phone   = aerodallas_detail( 'phone' );
$aerodallas_email   = sanitize_email( aerodallas_detail( 'email' ) );
$aerodallas_insta   = aerodallas_detail( 'instagram_url' );
$aerodallas_address = aerodallas_detail( 'address' );
$aerodallas_hours   = aerodallas_detail( 'hours' );

$aerodallas_actions = array(
	array(
		'label' => __( 'Request a Quote', 'aero-dallas' ),
		'url'   => '#quote',
	),
);
if ( $aerodallas_phone ) {
	$aerodallas_actions[] = array(
		/* translators: %s: phone number. */
		'label' => sprintf( __( 'Call %s', 'aero-dallas' ), $aerodallas_phone ),
		'url'   => aerodallas_phone_href(),
		'style' => 'explore',
		'icon'  => 'phone',
	);
}

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'eyebrow'   => aerodallas_field( 'eyebrow' ),
		'heading'   => aerodallas_field( 'heading' ),
		'highlight' => aerodallas_field( 'highlight' ),
		'lead_html' => $aerodallas_intro,
		'photo'     => 'band-truck-dusk',
		'actions'   => $aerodallas_actions,
	)
);

// Contact methods: only details that are filled in.
$aerodallas_methods = array();
if ( $aerodallas_phone ) {
	$aerodallas_methods[] = array(
		'icon'  => 'phone',
		'title' => $aerodallas_phone,
		'text'  => __( 'Call us', 'aero-dallas' ),
		'url'   => aerodallas_phone_href(),
	);
}
if ( $aerodallas_email ) {
	$aerodallas_methods[] = array(
		'icon'  => 'mail',
		'title' => $aerodallas_email,
		'text'  => __( 'Email us', 'aero-dallas' ),
		'url'   => 'mailto:' . $aerodallas_email,
	);
}
if ( $aerodallas_insta ) {
	$aerodallas_methods[] = array(
		'icon'     => 'instagram',
		'title'    => aerodallas_instagram_handle(),
		'text'     => __( 'Message us on Instagram', 'aero-dallas' ),
		'url'      => $aerodallas_insta,
		'external' => true,
	);
}
if ( $aerodallas_address ) {
	$aerodallas_methods[] = array(
		'icon'  => 'pin',
		'title' => preg_replace( '/\s*\n\s*/', ', ', $aerodallas_address ),
		'text'  => __( 'Address', 'aero-dallas' ),
	);
}
if ( $aerodallas_hours ) {
	$aerodallas_methods[] = array(
		'icon'  => 'clock',
		'title' => preg_replace( '/\s*\n\s*/', ' · ', $aerodallas_hours ),
		'text'  => __( 'Business hours', 'aero-dallas' ),
	);
}
$aerodallas_methods[] = array(
	'icon'  => 'document',
	'title' => __( 'Online quote request', 'aero-dallas' ),
	'text'  => __( 'Send your requirements below', 'aero-dallas' ),
	'url'   => '#quote',
);

get_template_part(
	'template-parts/hero-panel',
	null,
	array(
		'label' => __( 'Ways to contact us', 'aero-dallas' ),
		'items' => $aerodallas_methods,
		'cols'  => min( 4, count( $aerodallas_methods ) ),
	)
);

$aerodallas_next = array_slice( aerodallas_items( 'aerodallas_step' ), 0, 4 );
?>

<div class="section contact-main">
	<div class="container contact-layout">
		<aside class="next-steps" aria-labelledby="next-title">
			<p class="eyebrow"><?php esc_html_e( 'What Happens Next', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="next-title"><?php echo aerodallas_highlight( __( 'From Request to Quotation', 'aero-dallas' ), __( 'Quotation', 'aero-dallas' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<?php if ( $aerodallas_next ) : ?>
				<ol class="next-steps__list">
					<?php foreach ( $aerodallas_next as $aerodallas_i => $aerodallas_step ) : ?>
						<li>
							<span class="next-steps__num" aria-hidden="true"><?php echo esc_html( (string) ( $aerodallas_i + 1 ) ); ?></span>
							<div>
								<h3 class="next-steps__title"><?php echo esc_html( get_the_title( $aerodallas_step ) ); ?></h3>
								<?php if ( has_excerpt( $aerodallas_step ) ) : ?>
									<p><?php echo esc_html( get_the_excerpt( $aerodallas_step ) ); ?></p>
								<?php endif; ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ol>
			<?php endif; ?>
			<div class="next-steps__card">
				<span class="icon-badge"><?php echo aerodallas_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<p>
					<?php esc_html_e( 'Your details are used to respond to your request.', 'aero-dallas' ); ?>
					<a href="<?php echo esc_url( aerodallas_page_url( 'policies', 'privacy' ) ); ?>"><?php esc_html_e( 'Read our Privacy Policy', 'aero-dallas' ); ?></a>
				</p>
			</div>
		</aside>
		<section class="quote-panel" id="quote" aria-labelledby="quote-title" tabindex="-1">
			<h2 class="quote-panel__title" id="quote-title"><?php echo esc_html( aerodallas_field( 'form_heading' ) ); ?></h2>
			<p class="quote-panel__note"><?php esc_html_e( 'Fields marked * are required.', 'aero-dallas' ); ?></p>
			<?php get_template_part( 'template-parts/quote-form' ); ?>
		</section>
	</div>
</div>

<?php
get_template_part( 'template-parts/faq', null, array( 'heading' => aerodallas_field( 'faq_heading' ) ) );
get_footer();
