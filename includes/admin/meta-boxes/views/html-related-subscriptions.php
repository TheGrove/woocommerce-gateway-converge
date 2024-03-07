<div class="wgc_related_items">
	<table>
		<thead>
		<tr>
			<th><?php esc_html_e( 'Subscription Number', 'elavon-converge-gateway' ); ?></th>
			<th><?php esc_html_e( 'Status', 'elavon-converge-gateway' ); ?></th>
			<th><?php esc_html_e( 'Total', 'elavon-converge-gateway' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( (array) $subscriptions as $subscription ) : ?>
			<tr>
				<td>
					<a href="<?php echo esc_url( get_edit_post_link( $subscription->get_id() ) ); ?>">#<?php echo esc_html( $subscription->get_order_number() ); ?></a>
				</td>
				<td><?php echo esc_html( $subscription->get_status() ); ?></td>
				<td><?php echo wp_kses_post( wc_price( $subscription->get_total() ) ); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>