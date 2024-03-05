jQuery(function ($) {
	$.payment.cards.unshift({
		type: 'visa19',
		patterns: [4],
		format: /(\d{1,4})/g,
		length: [19],
		cvcLength: [3],
		luhn: true,
	});

	if (
		$('input[type=radio][name=payment_method]').val() ===
		'elavon-converge-gateway'
	) {
		requireElements(true);
	} else {
		requireElements(false);
	}

	$('input[type=radio][name=payment_method]').change(function () {
		if (this.value === 'elavon-converge-gateway') {
			requireElements(true);
		} else {
			requireElements(false);
		}
	});

	if ($('#elavon-converge-gateway_stored_card_new').length) {
		if (
			$(
				'input[type=radio][name=elavon-converge-gateway_stored_card]'
			).val() === 'elavon-converge-gateway_new_card'
		) {
			requireElements(true);
		} else {
			requireElements(false);
		}

		$('input[type=radio][name=elavon-converge-gateway_stored_card]').change(
			function () {
				if (this.value === 'elavon-converge-gateway_new_card') {
					requireElements(true);
				} else {
					requireElements(false);
				}
			}
		);
	}

	function requireElements(prop) {
		$('#elavon-converge-gateway-card-number').prop('required', prop);
		$('#elavon-converge-gateway-card-expiry').prop('required', prop);
		$('#elavon-converge-gateway-card-cvc').prop('required', prop);
	}
});


