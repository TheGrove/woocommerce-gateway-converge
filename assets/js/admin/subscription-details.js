/* eslint-disable no-alert, eqeqeq */
/* global ajaxurl, alert, confirm */
jQuery(function ($) {
	$('.refund-items').hide();

	$('body').on('click', '.wgc_btn_create_order', function (e) {
		e.preventDefault();

		const confirmMessage =
			'Are you sure you wish to create the order? This action cannot be undone.';
		if (confirm(confirmMessage)) {
			const $me = $(this),
				action = 'wgc_create_order_ajax_action',
				newOrderTransactionId = this.value,
				subscriptionId = $('#wgc_subscription_id').val(),
				nonce = $('#wgc_new_order_txn_nonce').val();

			const data = $.extend(true, $me.data(), {
				action,
				new_order_transaction_id: newOrderTransactionId,
				subscription_id: subscriptionId,
				nonce,
			});

			$.post(ajaxurl, data, function (r) {
				const response = JSON.parse(r);
				const message = response.message;
				if (response.success) {
					$('#wpwrap').css('opacity', '0.5');
					alert(message);
					window.location.href = window.location.href;
				} else {
					$('.wgc_related_items .error').remove();
					alert(message);
				}
			});
		}
	});

	function updateStatus(update) {
		const $me = $(this),
			action = 'wgc_sync_subscription_ajax_action';
		const data = $.extend(true, $me.data(), {
			action,
			dataType: 'json',
			form_data: {
				subscription_id: $('#post_ID').val(),
				update,
			},
		});

		$.post(ajaxurl, data, function (response) {
			response = JSON.parse(response);
			if (response.display == true) {
				$('.wgc_sync_container').show();
			} else if (update == true) {
				$('#wpwrap').css('opacity', '0.5');
				window.location.href = window.location.href;
				return false;
			}
		});
	}
	$('body').on('click', '.wgc_sync', function (e) {
		e.preventDefault();
		updateStatus(true);
	});
	updateStatus(false);
});
