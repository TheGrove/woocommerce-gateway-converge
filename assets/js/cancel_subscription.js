jQuery(function ($) {
    $('.wgc_cancel_subscription').click(function (e) {
        if (confirm(elavon_converge_gateway.cancel_alert)) {
        } else {
            e.preventDefault();
        }
    });
});
