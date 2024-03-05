/* global elavon_converge_gateway */
jQuery(function ($) {
	function initialCheck() {
		$('[id^="wgc_plan_introductory_rate_cb"]').each(function () {
			if (this.checked) {
				$(this)
					.parent()
					.parent()
					.find('[id^="wgc_plan_introductory_rate_fields"]')
					.css('display', 'inline-block');
			}
		});

		$('[id^="wgc_plan_billing_frequency_select"]').each(function () {
			const billingFreq = $(this).val();
			const convergeOptions = $(this).parent().parent().parent();

			if (billingFreq === 'month') {
				convergeOptions
					.find(
						'[id^="wgc_plan_billing_frequency_fields_week_month"]'
					)
					.css('display', 'inline-block');
				convergeOptions
					.find(
						'[id^="wgc_plan_billing_frequency_count_field_month"]'
					)
					.css('display', 'inline-block');
			}
			if (billingFreq === 'week') {
				convergeOptions
					.find(
						'[id^="wgc_plan_billing_frequency_fields_week_month"]'
					)
					.css('display', 'inline-block');
				convergeOptions
					.find('[id^="wgc_plan_billing_frequency_count_field_week"]')
					.css('display', 'inline-block');
			}
		});
	}
	initialCheck();

	$('body').on(
		'change',
		'[id^="wgc_plan_introductory_rate_cb"]',
		function () {
			const convergeOptions = $(this).parent().parent();
			if (this.checked) {
				convergeOptions
					.find('[id^="wgc_plan_introductory_rate_fields"]')
					.css('display', 'inline-block');
				convergeOptions
					.find('[id^="wgc_plan_introductory_rate_amount"]')
					.prop('required', true);
				convergeOptions
					.find('[id^="wgc_plan_introductory_rate_billing_periods"]')
					.prop('required', true);
			} else {
				convergeOptions
					.find('[id^="wgc_plan_introductory_rate_fields"]')
					.hide();
				convergeOptions
					.find('[id^="wgc_plan_introductory_rate_amount"]')
					.prop('required', false);
				convergeOptions
					.find('[id^="wgc_plan_introductory_rate_billing_periods"]')
					.prop('required', false);
			}
		}
	);

	$('body').on(
		'change',
		'[id^="wgc_plan_billing_frequency_select"]',
		function () {
			const convergeOptions = $(this).parent().parent().parent();
			const billingFreq = this.value;
			if (billingFreq === 'month') {
				convergeOptions
					.find(
						'[id^="wgc_plan_billing_frequency_fields_week_month"]'
					)
					.css('display', 'inline-block');
				convergeOptions
					.find('[id^="wgc_plan_billing_frequency_count_field_week"]')
					.hide();
				convergeOptions
					.find(
						'[id^="wgc_plan_billing_frequency_count_field_month"]'
					)
					.css('display', 'inline-block');
			} else if (billingFreq === 'week') {
				convergeOptions
					.find(
						'[id^="wgc_plan_billing_frequency_fields_week_month"]'
					)
					.css('display', 'inline-block');
				convergeOptions
					.find(
						'[id^="wgc_plan_billing_frequency_count_field_month"]'
					)
					.hide();
				convergeOptions
					.find('[id^="wgc_plan_billing_frequency_count_field_week"]')
					.css('display', 'inline-block');
			} else {
				convergeOptions
					.find(
						'[id^="wgc_plan_billing_frequency_fields_week_month"]'
					)
					.hide();
			}
		}
	);

	$('body').on(
		'change',
		'input[type=radio][name^="wgc_plan_billing_ending"]',
		function () {
			const convergeOptions = $(this).closest('.wc_wgc_options_group');
			if (this.value === 'billing_periods') {
				convergeOptions
					.find('[id^="wgc_plan_ending_billing_periods"]')
					.prop('required', true);
			} else {
				convergeOptions
					.find('[id^="wgc_plan_ending_billing_periods"]')
					.prop('required', false);
			}
		}
	);

	$(document.body).on('woocommerce_variations_saved', function () {
		$('.wgc-notice').remove();
	});

	const displayVariationsCheckbox = function () {
		if ($('#product-type').val().indexOf('converge-variable') > -1) {
			$('.enable_variation').show();
			$('#general_product_data ._tax_status_field').parent().show();
		}
	};

	displayVariationsCheckbox();
	$(document.body).on(
		'woocommerce-product-type-change woocommerce_added_attribute reload woocommerce_variations_loaded',
		function () {
			displayVariationsCheckbox();
			initialCheck();
		}
	);

	function wgcShowTaxFields() {
		if (
			$('select#product-type').val() ===
			// eslint-disable-next-line camelcase
			elavon_converge_gateway.subscription_name
		) {
			$('#general_product_data ._tax_status_field')
				.parent()
				.detach()
				.insertAfter($('.wc_wgc_options_group'));
			$('.show_if_simple').show();
			$('.options_group.pricing ._regular_price_field').hide();
			$('.options_group.pricing ._sale_price_field').hide();
		}
	}

	wgcShowTaxFields();

	$('input#_downloadable, input#_virtual').change(function () {
		wgcShowTaxFields();
	});

	$('body').bind('woocommerce-product-type-change', function () {
		wgcShowTaxFields();
	});
	// Editing a variable product
	$('#variable_product_options').on(
		'change',
		'[name^="wgc_plan_price"]',
		function () {
			const matches = $(this)
				.attr('name')
				.match(/\[(.*?)\]/);

			if (matches) {
				const loopIndex = matches[1];
				$('[name="variable_regular_price[' + loopIndex + ']"]').val(
					$(this).val()
				);
				$('[name="variable_sale_price[' + loopIndex + ']"]').val('');
			}
		}
	);
});
