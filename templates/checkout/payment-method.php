<div id="wgc-payment-gateway">
	<?php if ( $gateway->uses_internet_explorer_or_edge() ): ?>
        <div class="red"><?php _e( 'Your browser is not supported or the browser version is outdated. In order to enjoy the full shopping experience, we recommend using the latest version of Chrome, Firefox or Safari.', 'elavon-converge-gateway' ) ?></div>
	<?php endif; ?>

	<?php if ( $description ): ?>
		<?php echo wpautop( wptexturize( $description ) ); // @codingStandardsIgnoreLine. ?>
	<?php endif; ?>
	<?php if ( $gateway->isSavePaymentMethodsEnabled() && is_user_logged_in() ): ?>

        <div id="wgc-payment-gateway-fields">
	        <?php if ( $tokens ): ?>
		        <?php if ( $gateway->is_subscription_change_method_page() ):
			        global $wp;
			        $subscription_id               = $wp->query_vars['converge-subscription-change-method'];
			        $converge_subscription         = wgc_get_gateway()->get_converge_api()->get_subscription( wc_get_order( $subscription_id )->get_transaction_id() );
			        $converge_subscription_card_id = wgc_get_subscription_stored_card_id( $converge_subscription );
		        endif; ?>
		        <?php $default_is_expired = false; ?>
		        <?php foreach ( $tokens as $token ): ?>
					<?php if ( $gateway->is_subscription_change_method_page() ):
				        $is_subscription_used_card = $converge_subscription_card_id == $token->get_token( wgc_get_payment_name() );
			        endif; ?>

                    <div><input type="radio" class="input-radio" id="<?php echo $gateway->id . $token->get_id() ?>"
                                name="<?php echo $gateway->stored_card_key ?>"
                                value="<?php echo $token->get_id() ?>"
		                    <?php if ( $token->is_expired() ) {
			                    echo 'disabled';
			                    if ( $gateway->is_subscription_change_method_page() ) {
				                    if ( $is_subscription_used_card ) {
					                    $default_is_expired = true;
				                    }
			                    } elseif ( $token->get_is_default() ) {
				                    $default_is_expired = true;
			                    }
		                    } elseif ( $gateway->is_subscription_change_method_page() ) {
			                    if ( $is_subscription_used_card ) {
				                    echo 'checked';
			                    }
		                    } elseif ( $token->get_is_default() ) {
			                    echo 'checked';
		                    } ?> >
                        </input>
                        <label for="<?php echo $gateway->id . $token->get_id() ?>"><?php echo $token->get_display_name() ?></label>
                    </div>
				<?php endforeach; ?>
                <div>
                    <input type="radio" class="input-radio use-new-card" id="<?php echo $gateway->new_card_key ?>"
                           name="<?php echo $gateway->stored_card_key ?>"
                           value="<?php echo $gateway->new_card_value ?>"
						<?php if ( $default_is_expired ) {
							echo 'checked';
						} ?> >
                    </input>
                    <label for="<?php echo $gateway->new_card_key ?>"><?php _e( 'Use new card', 'elavon-converge-gateway' ) ?></label>
                </div>
			<?php endif; ?>

			<?php if ( $gateway->can_store_one_more_card() ): ?>
                <div class="save-for-later-use" <?php if ( $tokens ): ?>style="display: none"<?php endif; ?>>
	                <?php if ( ( ! wgc_has_subscription_elements_in_cart() && ! wgc_order_from_merchant_view_has_subscription_elements() ) && ! $gateway->is_subscription_change_method_page() ): ?>
                        <input type="checkbox" id="<?php echo WGC_KEY_SAVE_FOR_LATER_USE; ?>"
                               name="<?php echo WGC_KEY_SAVE_FOR_LATER_USE; ?>" value="1"/>
                        <label for="<?php echo WGC_KEY_SAVE_FOR_LATER_USE; ?>"><?php _e( 'Save for later use', 'elavon-converge-gateway' ) ?></label>
                        <p class="save-for-later-use-message"><?php echo $gateway->get_option( WGC_KEY_SAVE_FOR_LATER_USE_MESSAGE ); ?></p>
					<?php else: ?>
                        <input
                        type="hidden" id="<?php echo WGC_KEY_SAVE_FOR_LATER_USE; ?>" name="<?php echo WGC_KEY_SAVE_FOR_LATER_USE; ?>" value="1">
                        <p class="save-for-later-use-message">
							<?php _e( 'Your info and card details will be saved. Subscriptions must be tied to your profile in order to process recurring payments.', 'elavon-converge-gateway' ) ?>
                        </p>
	                <?php endif; ?>
                </div>
			<?php endif; ?>
        </div>
	<?php endif; ?>
	<?php if ( ( wgc_has_subscription_elements_in_cart() && ! $gateway->is_subscription_change_method_page() ) || wgc_order_from_merchant_view_has_subscription_elements() ): ?>
        <p><?php echo stripslashes( $gateway->get_option( WGC_KEY_SUBSCRIPTIONS_DISCLOSURE_MESSAGE ) ); ?></p>
	<?php endif; ?>
</div>
<?php if ( $gateway->is_subscription_change_method_page() ): ?>
	<?php $gateway->cc_fields(); ?>
<?php endif; ?>