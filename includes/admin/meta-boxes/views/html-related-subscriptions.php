<?php
use Automattic\WooCommerce\Utilities\OrderUtil;
?>
<div class="wgc_related_items">
	<table>
		<thead>
		<tr>
			<th><?php _e( 'Subscription Number', 'elavon-converge-gateway' ); ?></th>
			<th><?php _e( 'Status', 'elavon-converge-gateway' ); ?></th>
			<th><?php _e( 'Total', 'elavon-converge-gateway' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( (array) $subscriptions as $subscription ): ?>
			<tr>
				<td>
					<?php if ( OrderUtil::custom_orders_table_usage_is_enabled() ) :
						$subscription_edit_url = add_query_arg( array(
							'page'   => $subscription instanceof WC_Converge_Subscription ? 'wc-orders--wgc_subscription' : 'wc-orders',
							'action' => 'edit',
							'id'     => $subscription->get_id(),
						), admin_url( 'admin.php' ) );
					?>
						<a href="<?php echo esc_url( $subscription_edit_url ); ?>">#<?php echo $subscription->get_order_number(); ?></a>
					<?php else : ?>
						<a href="<?php echo get_edit_post_link( $subscription->get_id() ); ?>">#<?php echo $subscription->get_order_number(); ?></a>
					<?php endif; ?>
				</td>
				<td><?php echo $subscription->get_status(); ?></td>
				<td><?php echo wc_price( $subscription->get_total() ); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>