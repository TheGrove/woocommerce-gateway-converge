<div class="wgc_related_items">
	<?php if ( $has_errors ) : ?>

		<?php if ( $response instanceof \Elavon\Converge2\Response\Response ): ?>
            <p><?php echo wgc_get_order_error_note( __( 'Failed transactions retrieval.', 'elavon-converge-gateway' ), $response ); ?></p>
		<?php else: ?>
            <p><?php echo __( 'Failed transactions retrieval.', 'elavon-converge-gateway' ); ?></p>
		<?php endif; ?>

	<?php elseif ( empty( $transactions ) ) : ?>
        <p><?php _e( 'There are no transactions associated with this subscription.', 'elavon-converge-gateway' ); ?></p>
	<?php else : ?>
        <input type="hidden" name="wgc_subscription_id" id="wgc_subscription_id"
               value="<?php echo $subscription->get_id() ?>"/>
        <input type="hidden" name="wgc_new_order_txn_nonce" id="wgc_new_order_txn_nonce"
               value="<?php echo wp_create_nonce( "wgc_new_order_txn_nonce" ); ?>"/>
        <table>
            <thead>
            <tr>
                <th><?php _e( 'Transaction ID', 'elavon-converge-gateway' ); ?></th>
                <th><?php _e( 'Transaction Date (BST)', 'elavon-converge-gateway' ); ?></th>
                <th><?php _e( 'Payment', 'elavon-converge-gateway' ); ?></th>
                <th><?php _e( 'Status', 'elavon-converge-gateway' ); ?></th>
                <th><?php _e( 'Amount', 'elavon-converge-gateway' ); ?></th>
                <th class="text_left"><?php _e( 'Order Number', 'elavon-converge-gateway' ); ?></th>
                <th class="text_left"><?php _e( 'Action', 'elavon-converge-gateway' ); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ( (array) $transactions as $transaction ) : ?>
				<?php $_order_id = wgc_get_order_by_transaction_id( $transaction->getId() ); ?>
                <tr>
                    <td><?php echo $transaction->getId(); ?></td>
                    <td><?php echo wgc_format_datetime( $transaction->getCreatedAt() ); ?></td>
                    <td><?php echo sprintf( "%s - %s", $transaction->getCard()->getScheme(), $transaction->getCard()->getLast4() ); ?></td>
                    <td><?php echo $transaction->getState(); ?></td>
                    <td><?php echo wc_price( $transaction->getTotalAmount() ); ?></td>
                    <td class="text_left">
						<?php if ( ! empty( $_order_id ) ): ?>
                            <a href="<?php echo get_edit_post_link( $_order_id ); ?>">#<?php echo $_order_id; ?></a>
						<?php else: ?>
							<?php _e( 'N/A', 'elavon-converge-gateway' ); ?>
						<?php endif; ?>
                    <td class="text_left">
						<?php if ( ! empty( $_order_id ) ): ?>
							<?php _e( 'N/A', 'elavon-converge-gateway' ); ?>
						<?php else: ?>
                            <button type="submit" name="wgc_btn_create_order[]" class="button wgc_btn_create_order"
                                    value="<?php echo $transaction->getId(); ?>"><?php _e( 'Create Order',
									'elavon-converge-gateway' ) ?>
                            </button>
						<?php endif; ?>
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
	<?php endif; ?>
</div>