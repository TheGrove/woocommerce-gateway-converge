jQuery(function ($) {
    $.payment.cards.unshift({
        type: 'visa19',
        patterns: [4],
        format: /(\d{1,4})/g,
        length: [19],
        cvcLength: [3],
        luhn: true
    });

    if ($('input[type=radio][name=payment_method]').val() === 'elavon-converge-gateway') {
        require_elements(true);
    } else {
        require_elements(false);
    }

    $('input[type=radio][name=payment_method]').change(function (e) {
        if (this.value === 'elavon-converge-gateway') {
            require_elements(true);
        } else {
            require_elements(false);
        }
    });

    if ($('#elavon-converge-gateway_stored_card_new').length) {
        if ($('input[type=radio][name=elavon-converge-gateway_stored_card]').val() === 'elavon-converge-gateway_new_card') {
            require_elements(true);
        } else {
            require_elements(false);
        }

        $('input[type=radio][name=elavon-converge-gateway_stored_card]').change(function (e) {
            if (this.value === 'elavon-converge-gateway_new_card') {
                require_elements(true);
            } else {
                require_elements(false);
            }
        });
    }

    function require_elements(prop) {
        $("#elavon-converge-gateway-card-number").prop('required', prop);
        $("#elavon-converge-gateway-card-expiry").prop('required', prop);
        $("#elavon-converge-gateway-card-cvc").prop('required', prop);
    }
});
