<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<tr class="cart-subtotal">
	<th><?php esc_html_e( 'Recurring Totals', 'elavon-converge-gateway' ); ?></th>
	<td data-title="<?php esc_attr_e( 'Recurring Totals', 'elavon-converge-gateway' ); ?>"></td>
</tr>

<?php foreach ( $recurring_totals_elements as $key => $recurring_totals_element ) : ?>
	<?php if ( count( $recurring_totals_element ) > 0 ) : ?>
		<?php $index = 0; ?>
		<?php foreach ( $recurring_totals_element as $label ) : ?>
			<tr>
				<td class="product-name">
					<?php
					if ( 0 === $index && 'subtotal' === $key ) :
						esc_html_e( 'Subtotal', 'elavon-converge-gateway' );
endif;
					?>
					<?php
					if ( 0 === $index && 'discount' === $key ) :
						esc_html_e( 'Coupon', 'elavon-converge-gateway' );
endif;
					?>
					<?php
					if ( 0 === $index && 'shipping' === $key ) :
						esc_html_e( 'Shipping', 'elavon-converge-gateway' );
endif;
					?>
					<?php
					if ( 0 === $index && 'taxes' === $key ) :
						esc_html_e( 'Taxes', 'elavon-converge-gateway' );
endif;
					?>
					<?php
					if ( 0 === $index && 'total' === $key ) :
						esc_html_e( 'Total', 'elavon-converge-gateway' );
endif;
					?>
				</td>
				<td class="product-total">
					<?php echo wp_kses_post( $label ); ?>
				</td>
			</tr>
			<?php ++$index; ?>
		<?php endforeach; ?>
	<?php endif; ?>
<?php endforeach; ?>
