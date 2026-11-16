<?php
/**
 * Inner page header with breadcrumb, eyebrow, heading and optional lead.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_title   = get_the_title();
$aerodallas_heading = (string) ( $args['heading'] ?? '' );
$aerodallas_heading = '' !== trim( $aerodallas_heading ) ? $aerodallas_heading : $aerodallas_title;
$aerodallas_eyebrow = (string) ( $args['eyebrow'] ?? '' );
$aerodallas_lead    = (string) ( $args['lead'] ?? '' );
?>
<header class="page-header">
	<div class="page-header__grid" aria-hidden="true"></div>
	<div class="container page-header__inner">
		<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aero-dallas' ); ?>">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aero-dallas' ); ?></a></li>
				<li aria-current="page"><?php echo esc_html( $aerodallas_title ); ?></li>
			</ol>
		</nav>
		<?php aerodallas_eyebrow( $aerodallas_eyebrow, 'eyebrow--light' ); ?>
		<h1 class="page-header__title"><?php echo esc_html( $aerodallas_heading ); ?></h1>
		<?php if ( $aerodallas_lead ) : ?>
			<p class="page-header__lead"><?php echo esc_html( $aerodallas_lead ); ?></p>
		<?php endif; ?>
	</div>
</header>
