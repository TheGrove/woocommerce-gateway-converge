<?php
defined( 'ABSPATH' ) || exit;
?>

<form id="add_payment_method" method="post">
    <div id="payment" class="woocommerce-Payment">
        <ul class="woocommerce-PaymentMethods payment_methods methods">
            <?php
            $gateway = wgc_get_gateway();
                ?>
                    <?php
                    if ( $gateway->has_fields() || $gateway->get_description() ) {
	                    echo '<div class="woocommerce-PaymentBox woocommerce-PaymentBox--' . esc_attr( $gateway->id ) . ' payment_box payment_method_' . esc_attr( $gateway->id ) . '" >';
	                    echo $payment_token->get_display_name();
	                    $gateway->change_card_details_fields();
                        echo '</div>';
                    }
                    ?>
        </ul>

        <div class="form-row">
	        <?php wp_nonce_field( 'wgc-edit-payment-method', 'wgc-edit-payment-method-nonce' ); ?>
            <button type="submit" class="woocommerce-Button woocommerce-Button--alt button alt" id="place_order" value="<?php esc_attr_e( 'Save payment method', 'elavon-converge-gateway' ); ?>"><?php esc_html_e( 'Save payment method', 'elavon-converge-gateway' ); ?></button>
        </div>
    </div>
</form>