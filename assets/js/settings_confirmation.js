/*global elavon_converge_gateway */
accountFieldsIds = ['woocommerce_elavon-converge-gateway_processor_account_id', 'woocommerce_elavon-converge-gateway_merchant_alias', 'woocommerce_elavon-converge-gateway_public_key', 'woocommerce_elavon-converge-gateway_secret_key'];
var initialValues = [];
storeInitialValues();
if (!allFieldsEmpty()) {
    saveButton = document.getElementsByName('save')[0];
    saveButton.addEventListener('click', settingsConfirmation);
}

jQuery(function ($) {

    var enable_save_payment_methods = $('#woocommerce_elavon-converge-gateway_enable_save_payment_methods');
    var enable_subscriptions = $('#woocommerce_elavon-converge-gateway_enable_subscriptions');

    enable_subscriptions.change(function () {
        enable_subscriptions_checkbox_functionality($(this), enable_save_payment_methods);
    });

    enable_subscriptions_checkbox_functionality(enable_subscriptions, enable_save_payment_methods);

    enable_save_payment_methods.click(function () {
        var attr = $(this).attr('readonly');
        if (typeof attr !== typeof undefined && attr !== false) {
            return false;
        }
    });
});

function enable_subscriptions_checkbox_functionality(enable_subscriptions_checkbox, enable_save_payment_methods_checkbox){
    if (enable_subscriptions_checkbox.is(":checked")) {
        enable_save_payment_methods_checkbox.attr('readonly', 'readonly');

        if (!enable_save_payment_methods_checkbox.is(':checked')) {
            enable_save_payment_methods_checkbox.prop('checked', true);
        }
    } else {
        enable_save_payment_methods_checkbox.removeAttr('readonly');
    }
}

function settingsConfirmation(event) {

    isAccountChanged = false;

    for (var index = 0; index < accountFieldsIds.length; index++) {
        if (initialValues[accountFieldsIds[index]] != document.getElementById(accountFieldsIds[index]).value) {
            isAccountChanged = true;
            break;
        }
    }

    if (isAccountChanged) {
        if (confirm(elavon_converge_gateway.delete_alert)) {
        } else {
            event.preventDefault()
            window.location.href = window.location.href;
        }
    }
}

function allFieldsEmpty() {
    allEmpty = true;
    for (var index = 0; index < accountFieldsIds.length; index++) {
        if (initialValues[accountFieldsIds[index]] !== '') {
            allEmpty = false;
        }
    }

    return allEmpty;
}

function storeInitialValues() {
    for (var index = 0; index < accountFieldsIds.length; index++) {
        initialValues[accountFieldsIds[index]] = document.getElementById(accountFieldsIds[index]).value;
    }

}