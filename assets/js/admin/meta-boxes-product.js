jQuery(function ($) {

    function initial_check(){
        $('[id^="wgc_plan_introductory_rate_cb"]').each(function () {
            if (this.checked) {
                $(this).parent().parent().find('[id^="wgc_plan_introductory_rate_fields"]').css('display', 'inline-block');
            }
        });

        $('[id^="wgc_plan_billing_frequency_select"]').each(function () {
            var billing_freq = $(this).val();
            var converge_options = $(this).parent().parent().parent();

            if (billing_freq === 'month') {
                converge_options.find('[id^="wgc_plan_billing_frequency_fields_week_month"]').css('display', 'inline-block');
                converge_options.find('[id^="wgc_plan_billing_frequency_count_field_month"]').css('display', 'inline-block');
            }
            if (billing_freq === 'week') {
                converge_options.find('[id^="wgc_plan_billing_frequency_fields_week_month"]').css('display', 'inline-block');
                converge_options.find('[id^="wgc_plan_billing_frequency_count_field_week"]').css('display', 'inline-block');
            }
        });


    }
    initial_check();


    $('body').on('change', '[id^="wgc_plan_introductory_rate_cb"]', function () {
        var converge_options = $(this).parent().parent();
        if (this.checked) {
            converge_options.find('[id^="wgc_plan_introductory_rate_fields"]').css('display', 'inline-block');
            converge_options.find('[id^="wgc_plan_introductory_rate_amount"]').prop('required', true);
            converge_options.find('[id^="wgc_plan_introductory_rate_billing_periods"]').prop('required', true);
        } else {
            converge_options.find('[id^="wgc_plan_introductory_rate_fields"]').hide();
            converge_options.find('[id^="wgc_plan_introductory_rate_amount"]').prop('required', false);
            converge_options.find('[id^="wgc_plan_introductory_rate_billing_periods"]').prop('required', false);
        }
    });

    $('body').on('change', '[id^="wgc_plan_billing_frequency_select"]', function () {
        var converge_options = $(this).parent().parent().parent();
        var billing_freq = this.value;
        if (billing_freq === 'month') {
            converge_options.find('[id^="wgc_plan_billing_frequency_fields_week_month"]').css('display', 'inline-block');
            converge_options.find('[id^="wgc_plan_billing_frequency_count_field_week"]').hide();
            converge_options.find('[id^="wgc_plan_billing_frequency_count_field_month"]').css('display', 'inline-block');
        } else if (billing_freq === 'week') {
            converge_options.find('[id^="wgc_plan_billing_frequency_fields_week_month"]').css('display', 'inline-block');
            converge_options.find('[id^="wgc_plan_billing_frequency_count_field_month"]').hide();
            converge_options.find('[id^="wgc_plan_billing_frequency_count_field_week"]').css('display', 'inline-block');
        } else {
            converge_options.find('[id^="wgc_plan_billing_frequency_fields_week_month"]').hide();
        }
    });

    $('body').on('change', 'input[type=radio][name^="wgc_plan_billing_ending"]', function () {
        var converge_options = $(this).closest('.wc_wgc_options_group');
        if (this.value === 'billing_periods') {
            converge_options.find('[id^="wgc_plan_ending_billing_periods"]').prop('required', true);
        } else {
            converge_options.find('[id^="wgc_plan_ending_billing_periods"]').prop('required', false);
        }
    });

    $(document.body).on('woocommerce_variations_saved',  function () {
        $('.wgc-notice').remove();
    });

    var display_variations_checkbox = function(){
        if ($('#product-type').val().indexOf('converge-variable') > -1) {
            $('.enable_variation').show();
            $('#general_product_data ._tax_status_field').parent().show();
        }
    }

    display_variations_checkbox();
    $(document.body).on('woocommerce-product-type-change woocommerce_added_attribute reload woocommerce_variations_loaded', function () {
        display_variations_checkbox();
        initial_check();
    });

    function wgc_show_tax_fields() {
        if ($('select#product-type').val() == elavon_converge_gateway.subscription_name) {
            $('#general_product_data ._tax_status_field').parent().detach().insertAfter($('.wc_wgc_options_group'));
            $('.show_if_simple').show();
            $('.options_group.pricing ._regular_price_field').hide();
            $('.options_group.pricing ._sale_price_field').hide();
        }
    }

    wgc_show_tax_fields();

    $('input#_downloadable, input#_virtual').change(function () {
        wgc_show_tax_fields();
    });

    $('body').bind('woocommerce-product-type-change', function () {
        wgc_show_tax_fields();
    });
    // Editing a variable product
    $('#variable_product_options').on('change','[name^="wgc_plan_price"]',function(){
        var matches = $(this).attr('name').match(/\[(.*?)\]/);

        if (matches) {
            var loopIndex = matches[1];
            $('[name="variable_regular_price['+loopIndex+']"]').val($(this).val());
            $('[name="variable_sale_price['+loopIndex+']"]').val('');
        }
    });

});