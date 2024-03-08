<?php
use Automattic\WooCommerce\Utilities\OrderUtil;
?>
<div class="wgc_related_items">
	<table>
		<thead>
		<tr>
			<th><?php esc_html_e( 'Order Number', 'elavon-converge-gateway' ); ?></th>
			<th><?php esc_html_e( 'Relationship', 'elavon-converge-gateway' ); ?></th>
			<th><?php esc_html_e( 'Status', 'elavon-converge-gateway' ); ?></th>
			<th><?php esc_html_e( 'Total', 'elavon-converge-gateway' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( (array) $orders as $order ): ?>
			<tr>
				<td>
					<a href="<?php echo esc_url( $order->get_edit_order_url() ); ?>">#<?php echo esc_html( $order->get_order_number() ); ?></a>
				</td>
				<td>
					<?php if ( $order->get_meta( '_renewal_order', true ) ) : ?>
					<?php esc_html_e( 'Renewal Order', 'elavon-converge-gateway' ) ?></td>
				<?php else: ?>
					<?php esc_html_e( 'Parent Order', 'elavon-converge-gateway' ) ?></td>
				<?php endif; ?>
				<td><?php echo esc_html( $order->get_status() ); ?></td>
				<td><?php echo wp_kses_post( wc_price( $order->get_total() ) ); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

</div>