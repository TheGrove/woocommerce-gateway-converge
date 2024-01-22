<?php if ( empty( $subscriptions ) ): ?>
	<?php printf( __( 'You have no subscriptions.', 'elavon-converge-gateway' ) ); ?>
<?php else: ?>
    <table class="woocommerce_subscriptions_table">
        <thead>
        <tr>
            <th><?php _e( 'Subscription', 'elavon-converge-gateway' ) ?></th>
            <th><?php _e( 'Product', 'elavon-converge-gateway' ) ?></th>
            <th><?php _e( 'Total', 'elavon-converge-gateway' ) ?></th>
            <th><?php _e( 'Actions', 'elavon-converge-gateway' ) ?></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $subscriptions as $subscription ): ?>
            <tr>
                <td>
                    <a href="<?php echo $subscription->get_view_subscription_url() ?>">#<?php echo $subscription->get_order_number() ?></a>
                </td>
                <td>
	                <?php foreach($subscription->get_items('line_item') as $item_id => $item):?>
		                <?php echo $item->get_name()?>
                        <br>
	                <?php endforeach;?>
                </td>
                <td>
	                <?php echo wc_price( $subscription->get_total() ); ?>
                </td>
                <td>
                    <a class="button"
                       href="<?php echo $subscription->get_view_subscription_url() ?>"><?php echo __( 'View',
							'elavon-converge-gateway' ) ?>
                    </a>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>