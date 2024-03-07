<div class="wgc_related_items">
	<?php if ( $has_errors ) : ?>

		<?php if ( $response instanceof \Elavon\Converge2\Response\Response ) : ?>
			<p><?php echo esc_html( wgc_get_order_error_note( __( 'Failed transactions retrieval.', 'elavon-converge-gateway' ), $response ) ); ?></p>
		<?php else : ?>
			<p><?php echo esc_html__( 'Failed transactions retrieval.', 'elavon-converge-gateway' ); ?></p>
		<?php endif; ?>

	<?php elseif ( empty( $transactions ) ) : ?>
		<p><?php esc_html_e( 'There are no transactions associated with this subscription.', 'elavon-converge-gateway' ); ?></p>
	<?php else : ?>
		<input type="hidden" name="wgc_subscription_id" id="wgc_subscription_id"
				value="<?php echo esc_attr( $subscription->get_id() ); ?>"/>
		<input type="hidden" name="wgc_new_order_txn_nonce" id="wgc_new_order_txn_nonce"
				value="<?php echo esc_attr( wp_create_nonce( 'wgc_new_order_txn_nonce' ) ); ?>"/>
		<table>
			<thead>
			<tr>
				<th><?php esc_html_e( 'Transaction ID', 'elavon-converge-gateway' ); ?></th>
				<th><?php esc_html_e( 'Transaction Date (BST)', 'elavon-converge-gateway' ); ?></th>
				<th><?php esc_html_e( 'Payment', 'elavon-converge-gateway' ); ?></th>
				<th><?php esc_html_e( 'Status', 'elavon-converge-gateway' ); ?></th>
				<th><?php esc_html_e( 'Amount', 'elavon-converge-gateway' ); ?></th>
				<th class="text_left"><?php esc_html_e( 'Order Number', 'elavon-converge-gateway' ); ?></th>
				<th class="text_left"><?php esc_html_e( 'Action', 'elavon-converge-gateway' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( (array) $transactions as $transaction ) : ?>
				<?php $_order_id = wgc_get_order_by_transaction_id( $transaction->getId() ); ?>
				<tr>
					<td><?php echo esc_html( $transaction->getId() ); ?></td>
					<td><?php echo esc_html( wgc_format_datetime( $transaction->getCreatedAt() ) ); ?></td>
					<td><?php printf( '%s - %s', esc_html( $transaction->getCard()->getScheme() ), esc_html( $transaction->getCard()->getLast4() ) ); ?></td>
					<td><?php echo esc_html( $transaction->getState() ); ?></td>
					<td><?php echo wp_kses_post( wc_price( $transaction->getTotalAmount() ) ); ?></td>
					<td class="text_left">
						<?php if ( ! empty( $_order_id ) ) : ?>
							<a href="<?php echo esc_url( get_edit_post_link( $_order_id ) ); ?>">#<?php echo esc_html( $_order_id ); ?></a>
						<?php else : ?>
							<?php esc_html_e( 'N/A', 'elavon-converge-gateway' ); ?>
						<?php endif; ?>
					<td class="text_left">
						<?php if ( ! empty( $_order_id ) ) : ?>
							<?php esc_html_e( 'N/A', 'elavon-converge-gateway' ); ?>
						<?php else : ?>
							<button type="submit" name="wgc_btn_create_order[]" class="button wgc_btn_create_order"
									value="<?php echo esc_attr( $transaction->getId() ); ?>">
														<?php
														esc_html_e(
															'Create Order',
															'elavon-converge-gateway'
														)
														?>
							</button>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>