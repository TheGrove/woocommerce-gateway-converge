jQuery(function ($) {
	$('.wgc_cancel_subscription').click(function (e) {
		// eslint-disable-next-line no-alert, no-undef, camelcase
		if (confirm(elavon_converge_gateway.cancel_alert)) {
		} else {
			e.preventDefault();
		}
	});
});
