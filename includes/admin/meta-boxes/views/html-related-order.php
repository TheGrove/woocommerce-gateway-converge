<div class="wgc_related_items">

    <table>
        <thead>
        <tr>
            <th><?php _e( 'Order Number', 'elavon-converge-gateway' ); ?></th>
            <th><?php _e( 'Relationship', 'elavon-converge-gateway' ); ?></th>
            <th><?php _e( 'Status', 'elavon-converge-gateway' ); ?></th>
            <th><?php _e( 'Total', 'elavon-converge-gateway' ); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( (array) $orders as $order ): ?>
            <tr>
                <td>
                    <a href="<?php echo get_edit_post_link( $order->get_id() ); ?>">#<?php echo $order->get_order_number(); ?></a>
                </td>
                <td>
					<?php if ( get_post_meta( $order->get_id(), '_renewal_order', true ) ) : ?>
					<?php _e( 'Renewal Order', 'elavon-converge-gateway' ) ?></td>
				<?php else: ?>
					<?php _e( 'Parent Order', 'elavon-converge-gateway' ) ?></td>
				<?php endif; ?>
                <td><?php echo $order->get_status(); ?></td>
                <td><?php echo wc_price( $order->get_total() ); ?></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>

</div>