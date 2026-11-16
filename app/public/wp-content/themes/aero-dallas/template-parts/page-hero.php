<?php
/**
 * Inner page hero: full-width photo (light or dark treatment by mode),
 * breadcrumb, eyebrow, heading with highlight, intro and actions.
 *
 * Args:
 * - eyebrow, heading, highlight (string)
 * - lead (string, plain text) or lead_html (string, already escaped HTML)
 * - photo (bundled photo key; a featured image on the page replaces it)
 * - actions (array of array{label:string,url:string,style:string,icon?:string})
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_title   = get_the_title();
$aerodallas_heading = trim( (string) ( $args['heading'] ?? '' ) );
$aerodallas_heading = '' !== $aerodallas_heading ? $aerodallas_heading : $aerodallas_title;
$aerodallas_thumb   = (int) get_post_thumbnail_id();
$aerodallas_actions = $args['actions'] ?? array();
?>
<section class="page-hero" aria-labelledby="page-hero-title">
	<div class="page-hero__bg" aria-hidden="true">
		<?php echo aerodallas_image( $aerodallas_thumb, (string) ( $args['photo'] ?? '' ), array( 'sizes' => '100vw', 'eager' => true, 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
	</div>
	<div class="container page-hero__inner">
		<div class="page-hero__content">
			<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aero-dallas' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aero-dallas' ); ?></a></li>
					<li aria-current="page"><?php echo esc_html( $aerodallas_title ); ?></li>
				</ol>
			</nav>
			<?php aerodallas_eyebrow( (string) ( $args['eyebrow'] ?? '' ) ); ?>
			<h1 class="page-hero__title" id="page-hero-title"><?php echo aerodallas_highlight( $aerodallas_heading, (string) ( $args['highlight'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h1>
			<?php if ( ! empty( $args['lead_html'] ) ) : ?>
				<div class="page-hero__lead"><?php echo $args['lead_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- caller passes escaped / the_content output. ?></div>
			<?php elseif ( ! empty( $args['lead'] ) ) : ?>
				<div class="page-hero__lead"><p><?php echo esc_html( $args['lead'] ); ?></p></div>
			<?php endif; ?>
			<?php if ( $aerodallas_actions ) : ?>
				<div class="page-hero__actions">
					<?php foreach ( $aerodallas_actions as $aerodallas_action ) : ?>
						<?php if ( 'explore' === ( $aerodallas_action['style'] ?? '' ) ) : ?>
							<a class="hero__explore" href="<?php echo esc_url( $aerodallas_action['url'] ); ?>"<?php echo ! empty( $aerodallas_action['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>>
								<span class="hero__explore-icon"><?php echo aerodallas_icon( $aerodallas_action['icon'] ?? 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<?php echo esc_html( $aerodallas_action['label'] ); ?>
								<?php if ( ! empty( $aerodallas_action['external'] ) ) : ?>
									<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'aero-dallas' ); ?></span>
								<?php endif; ?>
							</a>
						<?php else : ?>
							<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $aerodallas_action['url'] ); ?>"><?php echo esc_html( $aerodallas_action['label'] ); ?></a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
