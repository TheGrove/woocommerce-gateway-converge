<?php if ( empty( $subscriptions ) ) : ?>
	<?php esc_html_e( 'You have no subscriptions.' ); ?>
<?php else : ?>
	<table class="woocommerce_subscriptions_table">
		<thead>
		<tr>
			<th><?php esc_html_e( 'Subscription', 'elavon-converge-gateway' ); ?></th>
			<th><?php esc_html_e( 'Product', 'elavon-converge-gateway' ); ?></th>
			<th><?php esc_html_e( 'Total', 'elavon-converge-gateway' ); ?></th>
			<th><?php esc_html_e( 'Actions', 'elavon-converge-gateway' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $subscriptions as $subscription ) : ?>
			<tr>
				<td>
					<a href="<?php echo esc_url( $subscription->get_view_subscription_url() ); ?>">#<?php echo esc_html( $subscription->get_order_number() ); ?></a>
				</td>
				<td>
					<?php foreach ( $subscription->get_items( 'line_item' ) as $item_id => $item ) : ?>
						<?php echo esc_html( $item->get_name() ); ?>
						<br>
					<?php endforeach; ?>
				</td>
				<td>
					<?php echo wp_kses_post( wc_price( $subscription->get_total() ) ); ?>
				</td>
				<td>
					<a class="button"
						href="<?php echo esc_url( $subscription->get_view_subscription_url() ); ?>">
										<?php
										echo esc_html__(
											'View',
											'elavon-converge-gateway'
										)
										?>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>