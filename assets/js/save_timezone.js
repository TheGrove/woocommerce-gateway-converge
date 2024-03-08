// eslint-disable-next-line no-undef
if (navigator.cookieEnabled && Intl !== undefined) {
	document.cookie =
		'wgc_timezone=' +
		Intl.DateTimeFormat().resolvedOptions().timeZone +
		';path=/';
}

jQuery(document).on(
	'change',
	'input[type=radio][name=elavon-converge-gateway_stored_card]:checked',
	function () {
		if (this.value === 'elavon-converge-gateway_new_card') {
			jQuery('.save-for-later-use').show();
			jQuery('#wc-elavon-converge-gateway-cc-form').show();
		} else {
			jQuery('.save-for-later-use').hide();
			jQuery('#wc-elavon-converge-gateway-cc-form').hide();
		}
	}
);

if (
	jQuery(
		'input[type=radio][name=elavon-converge-gateway_stored_card]:checked'
	).val() === 'elavon-converge-gateway_new_card'
) {
	jQuery('.save-for-later-use').show();
	jQuery('#wc-elavon-converge-gateway-cc-form').show();
} else {
	jQuery('.save-for-later-use').hide();
	jQuery('#wc-elavon-converge-gateway-cc-form').hide();
}
