<div class="wgc_converge_subscription_details">
    <table>
        <tr>
            <td><strong><?php _e('Subscription ID', 'elavon-converge-gateway') ?>:</strong></td>
            <td><?php echo $converge_subscription->id; ?></td>
        </tr>
        <tr>
            <td><strong><?php _e('Subscription State', 'elavon-converge-gateway') ?>:</strong></td>
            <td><?php echo $converge_subscription->subscriptionState; ?></td>
        </tr>
        <tr>
            <td><strong><?php _e('Start Date', 'elavon-converge-gateway') ?>:</strong></td>
            <td><?php echo wgc_format_datetime($converge_subscription->firstBillAt); ?></td>
        </tr>
        <tr>
            <td><strong><?php _e('Next Payment Date', 'elavon-converge-gateway') ?>:</strong></td>
            <td><?php echo $converge_subscription->nextBillAt ? wgc_format_datetime($converge_subscription->nextBillAt) : "-"; ?></td>
        </tr>
        <tr>
            <td><strong><?php _e('End Date', 'elavon-converge-gateway') ?>:</strong></td>
            <td><?php echo $converge_subscription->finalBillAt ? wgc_format_datetime($converge_subscription->finalBillAt) : "-"; ?></td>
        </tr>
        <tr>
            <td><strong><?php _e('Timezone', 'elavon-converge-gateway') ?>:</strong></td>
            <td><?php echo $converge_subscription->timeZoneId; ?></td>
        </tr>
        <tr>
            <td><strong><?php _e('Failure Count', 'elavon-converge-gateway') ?>:</strong></td>
            <td><?php echo $converge_subscription->failureCount; ?></td>
        </tr>
    </table>
</div>