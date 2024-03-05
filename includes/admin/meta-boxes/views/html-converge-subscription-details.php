<div class="wgc_converge_subscription_details">
	<table>
		<tr>
			<td><strong><?php _e( 'Subscription ID', 'elavon-converge-gateway' ); ?>:</strong></td>
			<td><?php echo esc_html( $converge_subscription->id ); ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Subscription State', 'elavon-converge-gateway' ); ?>:</strong></td>
			<td><?php echo esc_html( $converge_subscription->subscriptionState ); ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Start Date', 'elavon-converge-gateway' ); ?>:</strong></td>
			<td><?php echo esc_html( wgc_format_datetime( $converge_subscription )->firstBillAt ); ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Next Payment Date', 'elavon-converge-gateway' ); ?>:</strong></td>
			<td><?php echo $converge_subscription->nextBillAt ? esc_html( wgc_format_datetime( $converge_subscription->nextBillAt ) ) : '-'; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'End Date', 'elavon-converge-gateway' ); ?>:</strong></td>
			<td><?php echo $converge_subscription->finalBillAt ? esc_html( wgc_format_datetime( $converge_subscription->finalBillAt ) ) : '-'; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Timezone', 'elavon-converge-gateway' ); ?>:</strong></td>
			<td><?php echo esc_html( $converge_subscription->timeZoneId ); ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Failure Count', 'elavon-converge-gateway' ); ?>:</strong></td>
			<td><?php echo esc_html( $converge_subscription->failureCount ); ?></td>
		</tr>
	</table>
</div>