jQuery(function ($) {
	const changePayment = {
		init() {
			//payment method change.
			$('input[name="payment_method"]').on(
				'click',
				this.payment_gateway_selected
			);

			this.init_payment_methods();
		},
		init_payment_methods() {
			const paymentMethods = $('input[name="payment_method"]');

			if (paymentMethods.length === 1) {
				$(paymentMethods[0]).hide();
			}

			if (paymentMethods.filter(':checked').length === 0) {
				//choose the first payment method.
				$(paymentMethods[0]).prop('checked', true);
			}

			paymentMethods.filter(':checked').trigger('click');
		},
		payment_gateway_selected() {
			if ($('.payment_methods input.input-radio').length > 1) {
				const targetPaymentBox = $(
					'div.payment_box.' + $(this).attr('ID')
				);

				if (
					$(this).is(':checked') &&
					!targetPaymentBox.is(':visible')
				) {
					$('div.payment_box').filter(':visible').slideUp(250);

					if ($(this).is(':checked')) {
						$('div.payment_box.' + $(this).attr('ID')).slideDown(
							250
						);
					}
				}
			} else {
				$('div.payment_box').show();
			}

			if ($(this).data('order_button_text')) {
				$('#place_order').val($(this).data('order_button_text'));
			} else {
				$('#place_order').val($('#place_order').data('value'));
			}
		},
	};
	changePayment.init();
});
