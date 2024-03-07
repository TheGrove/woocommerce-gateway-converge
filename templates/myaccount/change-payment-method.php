<?php
wc_print_notices();
?>
<form id="order_review" method="post">
	<?php wp_nonce_field( 'change-payment-method', 'wgc_change_method_nonce' ); ?>
	<input type="hidden" name="wgc_subscription_id" value="<?php echo esc_attr( $subscription->get_id() ); ?>">
	<table>
		<tbody>
			<tr>
				<th><?php esc_html_e( 'Subscription', 'elavon-converge-gateway' ); ?></th>
				<td><a href="<?php echo esc_url( $subscription->get_view_subscription_url() ); ?>"><?php printf( esc_html__( '#%s', 'elavon-converge-gateway' ), esc_html( $subscription->get_order_number() ) ); ?></a></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Payment Method', 'elavon-converge-gateway' ); ?></th>
				<td>
					<?php
					if ( $used_card = wgc_get_subscription_used_stored_card( $converge_subscription ) ) :
						echo esc_html( $used_card->get_display_name() );
					else :
						echo esc_html( $subscription->get_payment_method_title() );
					endif;
					?>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Order Total', 'elavon-converge-gateway' ); ?></th>
				<td><?php echo wp_kses_post( $subscription->get_order()->get_formatted_order_total() ); ?></td>
			</tr>
		</tbody>
	</table>
	<?php if ( ! empty( $available_gateways ) ) : ?>
	<div id="payment">
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			foreach ( $available_gateways as $gateway ) {
				if ( $gateway->supports( 'wgc_subscriptions_change_payment_method' ) ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			}
			?>
		</ul>
	</div>
	<div class="form-row">
		<input type="submit" id="place_order" class="button alt" value="<?php esc_attr_e( 'Change Payment Method', 'elavon-converge-gateway' ); ?>" data-value="<?php esc_attr_e( 'Change Payment Method', 'elavon-converge-gateway' ); ?>">
	</div>
		<?php
	else :
		echo '<li>' . esc_html( apply_filters( 'woocommerce_no_available_payment_methods_message', __( 'No available payment methods for your location. Please contact us if you require assistance.', 'elavon-converge-gateway' ) ) ) . '</li>';
		?>
	<?php endif; ?>
</form>
