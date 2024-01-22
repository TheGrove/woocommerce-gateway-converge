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
                    <a href="<?php echo get_edit_post_link( $subscription->get_id() ); ?>">#<?php echo $subscription->get_order_number(); ?></a>
                </td>
                <td><?php echo $subscription->get_status(); ?></td>
                <td><?php echo wc_price( $subscription->get_total() ); ?></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
</div>