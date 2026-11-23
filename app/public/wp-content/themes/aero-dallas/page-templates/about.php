<?php
/**
 * Template Name: About Us
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
the_post();

$aerodallas_values = aerodallas_items( 'aerodallas_value' );
$aerodallas_icons  = array( 'shield', 'briefcase', 'target', 'gauge', 'scale', 'flag' );

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'eyebrow'   => aerodallas_field( 'eyebrow' ),
		'heading'   => aerodallas_field( 'heading' ),
		'highlight' => aerodallas_field( 'highlight' ),
		'lead'      => aerodallas_field( 'lead' ),
		'photo'     => 'about-welding',
		'actions'   => array(
			array(
				'label' => __( 'Get a Quote', 'aero-dallas' ),
				'url'   => aerodallas_quote_url(),
			),
			array(
				'label' => __( 'Explore Our Services', 'aero-dallas' ),
				'url'   => aerodallas_page_url( 'services' ),
				'style' => 'explore',
			),
		),
	)
);

get_template_part(
	'template-parts/hero-panel',
	null,
	array(
		'label' => __( 'Our services', 'aero-dallas' ),
		'items' => aerodallas_service_panel_items(),
	)
);
?>

<section class="section about-intro" aria-labelledby="about-intro-title">
	<div class="container about-intro__grid">
		<div class="about-intro__body" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'Our Company', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="about-intro-title"><?php echo aerodallas_highlight( __( 'Industrial Expertise, Dependable Support', 'aero-dallas' ), __( 'Dependable Support', 'aero-dallas' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<div class="prose prose--lead-first entry-content about-intro__text">
				<?php the_content(); ?>
			</div>
		</div>
		<?php
		get_template_part(
			'template-parts/photo-mosaic',
			null,
			array(
				'photos' => array(
					array(
						'attachment' => (int) get_theme_mod( 'aerodallas_about_image', 0 ),
						'photo'      => 'service-industrial',
						'label'      => __( 'Industrial support', 'aero-dallas' ),
					),
					array(
						'photo' => 'eq-aircraft-jack',
						'label' => __( 'Aviation ground support', 'aero-dallas' ),
					),
					array(
						'photo' => 'eq-roller-conveyor',
						'label' => __( 'Parts & equipment', 'aero-dallas' ),
					),
				),
			)
		);
		?>
	</div>
</section>

<?php if ( aerodallas_field( 'vision' ) || aerodallas_field( 'mission' ) ) : ?>
<section class="section section--alt vm-cards" aria-labelledby="vm-title">
	<div class="container">
		<header class="section-head section-head--center" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'Purpose', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="vm-title"><?php echo aerodallas_highlight( __( 'Our Vision & Mission', 'aero-dallas' ), __( 'Vision & Mission', 'aero-dallas' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		</header>
		<div class="vm-cards__grid">
			<?php
			$aerodallas_vm = array(
				'vision'  => array( __( 'Our Vision', 'aero-dallas' ), 'target' ),
				'mission' => array( __( 'Our Mission', 'aero-dallas' ), 'flag' ),
			);
			foreach ( $aerodallas_vm as $aerodallas_key => $aerodallas_vm_item ) :
				if ( ! aerodallas_field( $aerodallas_key ) ) {
					continue;
				}
				?>
				<article class="vm-card" data-reveal>
					<div class="vm-card__media" aria-hidden="true">
						<?php echo aerodallas_image( 'vision' === $aerodallas_key ? (int) get_theme_mod( 'aerodallas_detail_image', 0 ) : 0, 'vision' === $aerodallas_key ? 'detail-engine' : 'parts-detail', array( 'sizes' => '(min-width: 900px) 600px, 100vw', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="vm-card__body">
						<span class="icon-badge vm-card__icon"><?php echo aerodallas_icon( $aerodallas_vm_item[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<h3 class="vm-card__title"><?php echo esc_html( $aerodallas_vm_item[0] ); ?></h3>
						<p class="vm-card__text"><?php echo esc_html( aerodallas_field( $aerodallas_key ) ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $aerodallas_values ) : ?>
<section class="section" aria-labelledby="values-title">
	<div class="container">
		<header class="section-head section-head--center" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'What Guides Our Work', 'aero-dallas' ); ?></p>
			<h2 class="section-title" id="values-title"><?php echo esc_html( aerodallas_field( 'values_heading' ) ); ?></h2>
		</header>
		<ul class="values">
			<?php foreach ( $aerodallas_values as $aerodallas_i => $aerodallas_value ) : ?>
				<li class="values__item" data-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $aerodallas_i * 60 ) ); ?>ms">
					<span class="values__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $aerodallas_i + 1 ) ); ?></span>
					<span class="values__icon"><?php echo aerodallas_icon( aerodallas_item_icon( $aerodallas_value->ID, $aerodallas_i, $aerodallas_icons ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<h3 class="values__title"><?php echo esc_html( get_the_title( $aerodallas_value ) ); ?></h3>
					<?php if ( has_excerpt( $aerodallas_value ) ) : ?>
						<p><?php echo esc_html( get_the_excerpt( $aerodallas_value ) ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>

<?php
get_template_part(
	'template-parts/hsq',
	null,
	array(
		'heading' => aerodallas_field( 'hsq_heading' ),
		'alt'     => true,
	)
);

get_template_part(
	'template-parts/why-choose',
	null,
	array(
		'heading' => aerodallas_field( 'why_heading' ),
		'media'   => false,
	)
);
get_template_part( 'template-parts/cta-band' );
get_footer();
