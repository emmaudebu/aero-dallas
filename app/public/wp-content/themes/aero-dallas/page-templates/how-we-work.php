<?php
/**
 * Template Name: How We Work
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
the_post();

$aerodallas_steps = aerodallas_items( 'aerodallas_step' );
$aerodallas_icons = array( 'chat', 'magnifier', 'document', 'clipboard', 'wrench', 'handover', 'headset' );

ob_start();
the_content();
$aerodallas_intro = (string) ob_get_clean();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'eyebrow'   => aerodallas_field( 'eyebrow' ),
		'heading'   => aerodallas_field( 'heading' ),
		'highlight' => aerodallas_field( 'highlight' ),
		'lead_html' => $aerodallas_intro,
		'photo'     => 'band-night-gate',
		'actions'   => array(
			array(
				'label' => __( 'Start With a Quote', 'aero-dallas' ),
				'url'   => aerodallas_quote_url(),
			),
			array(
				'label' => __( 'See the Steps', 'aero-dallas' ),
				'url'   => '#steps',
				'style' => 'explore',
				'icon'  => 'arrow-down',
			),
		),
	)
);

$aerodallas_panel = array();
foreach ( array_slice( $aerodallas_steps, 0, 4 ) as $aerodallas_i => $aerodallas_step ) {
	$aerodallas_panel[] = array(
		'badge' => sprintf( '%02d', $aerodallas_i + 1 ),
		'title' => get_the_title( $aerodallas_step ),
		'url'   => '#step-' . ( $aerodallas_i + 1 ),
	);
}
get_template_part(
	'template-parts/hero-panel',
	null,
	array(
		'label' => __( 'First steps of the process', 'aero-dallas' ),
		'items' => $aerodallas_panel,
	)
);
?>

<?php if ( $aerodallas_steps ) : ?>
<section class="section zig" id="steps" aria-labelledby="steps-title">
	<div class="container">
		<header class="section-head section-head--center" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'Step by Step', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="steps-title">
				<?php
				/* translators: %d: number of steps. */
				echo aerodallas_highlight( sprintf( __( 'Our %d-Step Process', 'aero-dallas' ), count( $aerodallas_steps ) ), sprintf( __( '%d-Step Process', 'aero-dallas' ), count( $aerodallas_steps ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.I18n.MissingTranslatorsComment
				?>
			</h2>
		</header>
		<ol class="zig__list">
			<?php foreach ( $aerodallas_steps as $aerodallas_i => $aerodallas_step ) : ?>
				<li class="zig__item" id="step-<?php echo esc_attr( (string) ( $aerodallas_i + 1 ) ); ?>" data-reveal>
					<span class="zig__marker" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $aerodallas_i + 1 ) ); ?></span>
					<div class="zig__card">
						<span class="icon-badge"><?php echo aerodallas_icon( aerodallas_item_icon( $aerodallas_step->ID, $aerodallas_i, $aerodallas_icons ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<div>
							<p class="zig__count">
								<?php
								/* translators: 1: step number, 2: total steps. */
								echo esc_html( sprintf( __( 'Step %1$d of %2$d', 'aero-dallas' ), $aerodallas_i + 1, count( $aerodallas_steps ) ) );
								?>
							</p>
							<h3 class="zig__title"><?php echo esc_html( get_the_title( $aerodallas_step ) ); ?></h3>
							<?php if ( has_excerpt( $aerodallas_step ) ) : ?>
								<p class="zig__text"><?php echo esc_html( get_the_excerpt( $aerodallas_step ) ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
<?php endif; ?>

<?php if ( aerodallas_field( 'commitment_text' ) ) : ?>
<section class="commit-band" aria-labelledby="commitment-title">
	<div class="container commit-band__inner" data-reveal>
		<div class="commit-band__media" aria-hidden="true">
			<?php echo aerodallas_image( 0, 'service-industrial', array( 'sizes' => '(min-width: 1024px) 520px, 100vw', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<div class="commit-band__body">
			<span class="icon-badge"><?php echo aerodallas_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h2 class="commit-band__title" id="commitment-title"><?php echo esc_html( aerodallas_field( 'commitment_heading' ) ); ?></h2>
			<p class="commit-band__text"><?php echo esc_html( aerodallas_field( 'commitment_text' ) ); ?></p>
			<ul class="commit-band__pills">
				<li><?php esc_html_e( 'Transparency', 'aero-dallas' ); ?></li>
				<li><?php esc_html_e( 'Professionalism', 'aero-dallas' ); ?></li>
				<li><?php esc_html_e( 'Clear communication', 'aero-dallas' ); ?></li>
			</ul>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
