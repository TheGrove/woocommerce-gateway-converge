<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>


<tr>
    <th colspan="2">
		<?php _e( 'Recurring Totals', 'elavon-converge-gateway' ) ?>
    </th>
</tr>

<?php foreach ( $recurring_totals_elements as $key => $recurring_totals_element ): ?>
	<?php if ( count( $recurring_totals_element ) > 0 ): ?>
		<?php $index = 0; ?>
		<?php foreach ( $recurring_totals_element as $label ): ?>
            <tr>
                <td class="product-name">
	                <?php if ( 0 == $index && 'subtotal' == $key ): _e( 'Subtotal', 'elavon-converge-gateway' ); endif; ?>
	                <?php if ( 0 == $index && 'discount' == $key ): _e( 'Coupon', 'elavon-converge-gateway' ); endif; ?>
	                <?php if ( 0 == $index && 'shipping' == $key ): _e( 'Shipping', 'elavon-converge-gateway' ); endif; ?>
	                <?php if ( 0 == $index && 'taxes' == $key ): _e( 'Taxes', 'elavon-converge-gateway' ); endif; ?>
	                <?php if ( 0 == $index && 'total' == $key ): _e( 'Total', 'elavon-converge-gateway' ); endif; ?>
                </td>
                <td class="product-total">
					<?php echo $label; ?>
                </td>
            </tr>
			<?php $index ++; ?>
		<?php endforeach; ?>
	<?php endif; ?>
<?php endforeach; ?>
