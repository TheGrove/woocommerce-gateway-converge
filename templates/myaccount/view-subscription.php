<h1><?php echo esc_html_e( 'Converge subscription #', 'elavon-converge-gateway' ) . esc_html( $subscription->get_order_number() ); ?></h1>
<table>
	<tbody>
	<tr>
		<th><?php _e( 'Status', 'elavon-converge-gateway' ); ?></th>
		<td><?php echo esc_html( wgc_format_subscription_state( $converge_subscription->getSubscriptionState()->getValue() ) ); ?></td>
	</tr>
	<tr>
		<th><?php _e( 'Start date', 'elavon-converge-gateway' ); ?>
		<td><?php echo esc_html( wgc_format_subscription_date( $converge_subscription->getFirstBillAt() ) ); ?></td>
	</tr>
	<tr>
		<th><?php _e( 'End date', 'elavon-converge-gateway' ); ?></th>
		<td><?php echo esc_html( wgc_format_subscription_date( $converge_subscription->getFinalBillAt() ) ); ?></td>
	</tr>
	<tr>
		<th><?php _e( 'Next payment date', 'elavon-converge-gateway' ); ?></th>
		<td><?php echo esc_html( wgc_format_subscription_date( $converge_subscription->getNextBillAt() ) ); ?></td>
	</tr>
	<tr>
		<th><?php _e( 'Payment method', 'elavon-converge-gateway' ); ?></th>
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
	<?php
	if ( $subscription->is_cancellable() ) :
		$nonce = wp_create_nonce( 'cancel-subscription-' . $subscription->get_transaction_id() );
		?>
		<tr>
			<th><?php _e( 'Actions', 'elavon-converge-gateway' ); ?></th>
			<td>
				<a href="<?php echo esc_url( $subscription->get_change_subscription_payment_method_url() ); ?>"
					class="button">
					<?php
					_e(
						'Change payment method',
						'elavon-converge-gateway'
					)
					?>
				</a>
				<form method="post" style="display: inline">
					<button type="submit" name="cancel" value="cancel">
					<?php
					_e(
						'Cancel',
						'elavon-converge-gateway'
					)
					?>
					</button>
					<input type="hidden" name="cancel_wpnonce" value="<?php echo esc_attr( $nonce ); ?>">
				</form>
			</td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>
<h2><?php _e( 'Subscription totals', 'elavon-converge-gateway' ); ?></h2>
<table>
	<tbody>
	<?php foreach ( $subscription->get_items( 'line_item' ) as $item_id => $item ) : ?>
		<?php
		wc_get_template(
			'order/order-details-item.php',
			array(
				'order'              => $subscription,
				'item_id'            => $item_id,
				'item'               => $item,
				'show_purchase_note' => false,
				'purchase_note'      => '',
				'product'            => $item->get_product(),
			)
		)
		?>
	<?php endforeach; ?>
	<?php foreach ( $subscription->get_order_item_totals() as $key => $total ) : ?>
		<tr>
			<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
			<td><?php echo wp_kses_post( $total['value'] ); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<h2><?php _e( 'Related orders', 'elavon-converge-gateway' ); ?></h2>
<?php
$related_orders = wgc_get_subscription_related_orders( $subscription );
$has_orders     = (bool) $related_orders;

if ( ! $has_orders ) :
	printf( esc_html__( 'There are no orders associated with this subscription.', 'elavon-converge-gateway' ) );
else :
	?>
	<table>
		<thead>
		<tr>
			<th><?php _e( 'Order', 'elavon-converge-gateway' ); ?></th>
			<th><?php _e( 'Date', 'elavon-converge-gateway' ); ?></th>
			<th><?php _e( 'Status', 'elavon-converge-gateway' ); ?></th>
			<th><?php _e( 'Total', 'elavon-converge-gateway' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $related_orders as $order ) : ?>
			<tr>
				<td><a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
										<?php
										printf(
											'#%s',
											esc_attr( $order->get_order_number() )
										)
										?>
							</a></td>
				<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $order->get_date_created() ) ) ); ?></td>
				<td><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></td>
				<td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
