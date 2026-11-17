<?php
/**
 * 404 page.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="not-found">
	<div class="page-header__grid" aria-hidden="true"></div>
	<?php get_template_part( 'template-parts/flight-path' ); ?>
	<div class="container not-found__inner">
		<p class="not-found__code" aria-hidden="true">404</p>
		<h1 class="not-found__title"><?php esc_html_e( 'This page could not be found', 'aero-dallas' ); ?></h1>
		<p class="not-found__text"><?php esc_html_e( 'The link may be out of date, or the page may have moved. These pages should help:', 'aero-dallas' ); ?></p>
		<ul class="not-found__links">
			<li><a class="btn btn--primary" href="<?php echo esc_url( aerodallas_quote_url() ); ?>"><?php esc_html_e( 'Request a Quote', 'aero-dallas' ); ?></a></li>
			<li><a class="btn btn--ghost-light" href="<?php echo esc_url( aerodallas_page_url( 'services' ) ); ?>"><?php esc_html_e( 'Our Services', 'aero-dallas' ); ?></a></li>
			<li><a class="btn btn--ghost-light" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home page', 'aero-dallas' ); ?></a></li>
		</ul>
	</div>
</section>
<?php
get_footer();
