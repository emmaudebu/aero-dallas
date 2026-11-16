<?php
/**
 * Quick-quote bar under the light-mode hero. Sends the visitor to the full
 * quote form with the service, equipment and company already filled in.
 *
 * @package aero-dallas
 */

defined( 'ABSPATH' ) || exit;

$aerodallas_services = aerodallas_service_options();
?>
<div class="quick-quote">
	<form class="container quick-quote__form" method="get" action="<?php echo esc_url( aerodallas_page_url( 'contact' ) . '#quote' ); ?>" aria-label="<?php esc_attr_e( 'Start a quote request', 'aero-dallas' ); ?>">
		<div class="quick-quote__field">
			<span class="quick-quote__icon"><?php echo aerodallas_icon( 'clipboard' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<label for="qq-service"><?php esc_html_e( 'Service', 'aero-dallas' ); ?></label>
			<select id="qq-service" name="service">
				<option value=""><?php esc_html_e( 'Select a service', 'aero-dallas' ); ?></option>
				<?php foreach ( $aerodallas_services as $aerodallas_key => $aerodallas_label ) : ?>
					<option value="<?php echo esc_attr( $aerodallas_key ); ?>"><?php echo esc_html( $aerodallas_label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="quick-quote__field">
			<span class="quick-quote__icon"><?php echo aerodallas_icon( 'gear' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<label for="qq-equipment"><?php esc_html_e( 'Equipment type', 'aero-dallas' ); ?></label>
			<input type="text" id="qq-equipment" name="equipment" placeholder="<?php esc_attr_e( 'e.g. ground power unit', 'aero-dallas' ); ?>" maxlength="160">
		</div>
		<div class="quick-quote__field">
			<span class="quick-quote__icon"><?php echo aerodallas_icon( 'briefcase' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<label for="qq-company"><?php esc_html_e( 'Company', 'aero-dallas' ); ?></label>
			<input type="text" id="qq-company" name="company" placeholder="<?php esc_attr_e( 'Your company name', 'aero-dallas' ); ?>" maxlength="160" autocomplete="organization">
		</div>
		<button class="btn btn--dark quick-quote__submit" type="submit"><?php esc_html_e( 'Get a Quote', 'aero-dallas' ); ?></button>
	</form>
</div>
