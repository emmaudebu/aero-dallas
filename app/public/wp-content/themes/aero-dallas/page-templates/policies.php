<?php
/**
 * Template Name: Policies
 *
 * Hero with A–G shortcuts, then each policy (one per H2 in the page
 * content) as a card beside a sticky table of contents.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
the_post();

$aerodallas_policies = aerodallas_policy_sections( apply_filters( 'the_content', get_the_content() ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
$aerodallas_sections = $aerodallas_policies['sections'];

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'eyebrow'   => aerodallas_field( 'eyebrow' ),
		'heading'   => aerodallas_field( 'heading' ),
		'highlight' => aerodallas_field( 'highlight' ),
		'lead'      => aerodallas_field( 'lead' ),
		'photo'     => 'detail-engine',
		'actions'   => array(
			array(
				'label' => __( 'Ask a Question', 'aero-dallas' ),
				'url'   => aerodallas_page_url( 'contact' ),
			),
			array(
				'label' => __( 'Privacy Policy', 'aero-dallas' ),
				'url'   => '#privacy',
				'style' => 'explore',
				'icon'  => 'arrow-down',
			),
		),
	)
);
?>

<?php if ( $aerodallas_sections ) : ?>
	<div class="policy-chips">
		<div class="container">
			<ul class="policy-chips__list" aria-label="<?php esc_attr_e( 'Policies on this page', 'aero-dallas' ); ?>">
				<?php foreach ( $aerodallas_sections as $aerodallas_s ) : ?>
					<li>
						<a href="#<?php echo esc_attr( $aerodallas_s['id'] ); ?>">
							<?php if ( $aerodallas_s['letter'] ) : ?>
								<span class="policy-chips__letter" aria-hidden="true"><?php echo esc_html( $aerodallas_s['letter'] ); ?></span>
							<?php endif; ?>
							<span><?php echo esc_html( preg_replace( '/\s+Policy$/i', '', $aerodallas_s['name'] ) ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php endif; ?>

<div class="section policies-wrap">
	<div class="container policies">
		<?php if ( $aerodallas_sections ) : ?>
			<nav class="toc" aria-labelledby="toc-title">
				<h2 class="toc__title" id="toc-title"><?php esc_html_e( 'On this page', 'aero-dallas' ); ?></h2>
				<ol class="toc__list">
					<?php foreach ( $aerodallas_sections as $aerodallas_s ) : ?>
						<li><a href="#<?php echo esc_attr( $aerodallas_s['id'] ); ?>"><?php echo esc_html( $aerodallas_s['title'] ); ?></a></li>
					<?php endforeach; ?>
				</ol>
				<div class="toc__help">
					<p><?php esc_html_e( 'Questions about any of these policies?', 'aero-dallas' ); ?></p>
					<a class="link-arrow" href="<?php echo esc_url( aerodallas_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Contact us', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</div>
			</nav>
		<?php endif; ?>
		<div class="policies__body entry-content">
			<?php if ( $aerodallas_policies['intro'] ) : ?>
				<div class="prose prose--doc policy-intro"><?php echo $aerodallas_policies['intro']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output. ?></div>
			<?php endif; ?>
			<?php foreach ( $aerodallas_sections as $aerodallas_s ) : ?>
				<section class="policy-card" aria-labelledby="<?php echo esc_attr( $aerodallas_s['id'] ); ?>">
					<header class="policy-card__head">
						<?php if ( $aerodallas_s['letter'] ) : ?>
							<span class="policy-card__letter" aria-hidden="true"><?php echo esc_html( $aerodallas_s['letter'] ); ?></span>
						<?php endif; ?>
						<h2 class="policy-card__title" id="<?php echo esc_attr( $aerodallas_s['id'] ); ?>"><?php echo esc_html( $aerodallas_s['title'] ); ?></h2>
					</header>
					<div class="prose policy-card__body"><?php echo $aerodallas_s['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output. ?></div>
				</section>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
