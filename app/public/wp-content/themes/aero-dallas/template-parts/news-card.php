<?php
/**
 * Article card: photo with topic label, date, reading time, title, summary.
 *
 * Args:
 * - post (WP_Post)
 * - variant ('card' | 'feature') the feature card is wide with a larger title
 * - level (int) heading level, default 3
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_post = $args['post'] ?? null;
if ( ! $aerodallas_post instanceof WP_Post ) {
	return;
}
$aerodallas_feature = 'feature' === ( $args['variant'] ?? 'card' );
$aerodallas_level   = (int) ( $args['level'] ?? 3 );
$aerodallas_topic   = aerodallas_post_topic( $aerodallas_post );
$aerodallas_link    = get_permalink( $aerodallas_post );
$aerodallas_minutes = aerodallas_reading_time( $aerodallas_post );
?>
<article class="news-card<?php echo $aerodallas_feature ? ' news-card--feature' : ''; ?>">
	<div class="news-card__media">
		<?php echo aerodallas_post_image( $aerodallas_post, array( 'sizes' => $aerodallas_feature ? '(min-width: 1024px) 700px, 100vw' : '(min-width: 1100px) 400px, (min-width: 768px) 50vw, 100vw', 'alt' => '', 'eager' => $aerodallas_feature ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
		<?php if ( $aerodallas_topic ) : ?>
			<a class="news-card__topic" href="<?php echo esc_url( get_category_link( $aerodallas_topic ) ); ?>"><?php echo esc_html( $aerodallas_topic->name ); ?></a>
		<?php endif; ?>
	</div>
	<div class="news-card__body">
		<?php if ( $aerodallas_feature ) : ?>
			<p class="eyebrow"><?php echo is_sticky( $aerodallas_post->ID ) ? esc_html__( 'Featured Article', 'aero-dallas' ) : esc_html__( 'Latest Article', 'aero-dallas' ); ?></p>
		<?php endif; ?>
		<ul class="news-meta">
			<li><?php echo aerodallas_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><time datetime="<?php echo esc_attr( get_the_date( 'c', $aerodallas_post ) ); ?>"><?php echo esc_html( get_the_date( '', $aerodallas_post ) ); ?></time></li>
			<li><?php echo aerodallas_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php
				/* translators: %d: minutes. */
				echo esc_html( sprintf( _n( '%d min read', '%d min read', $aerodallas_minutes, 'aero-dallas' ), $aerodallas_minutes ) );
				?>
			</li>
		</ul>
		<?php
		printf(
			'<h%1$d class="news-card__title"><a href="%2$s">%3$s</a></h%1$d>',
			esc_attr( (string) $aerodallas_level ),
			esc_url( $aerodallas_link ),
			esc_html( get_the_title( $aerodallas_post ) )
		);
		?>
		<p class="news-card__summary"><?php echo esc_html( aerodallas_post_summary( $aerodallas_post, $aerodallas_feature ? 40 : 22 ) ); ?></p>
		<span class="news-card__more" aria-hidden="true"><?php esc_html_e( 'Read Article', 'aero-dallas' ); ?> <?php echo aerodallas_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	</div>
</article>
