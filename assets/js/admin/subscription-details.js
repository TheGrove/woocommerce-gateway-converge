jQuery(function ($) {

    $('.refund-items').hide();

    $('body').on('click', '.wgc_btn_create_order', function (e) {

        e.preventDefault();

        var confirm_message = "Are you sure you wish to create the order? This action cannot be undone.";
        if (confirm(confirm_message)) {
            var $me = $(this),
                action = 'wgc_create_order_ajax_action',
                new_order_transaction_id = this.value,
                subscription_id = $('#wgc_subscription_id').val(),
                nonce = $('#wgc_new_order_txn_nonce').val();

            var data = $.extend(true, $me.data(), {
                action: action,
                new_order_transaction_id: new_order_transaction_id,
                subscription_id: subscription_id,
                nonce: nonce
            });

            $.post(ajaxurl, data, function (r) {

                var response = JSON.parse(r);
                var message = response.message;
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


    function update_status(update){
        var $me = $(this),
            action = 'wgc_sync_subscription_ajax_action';
        var data = $.extend(true, $me.data(), {
            action: action,
            dataType: "json",
            form_data: {
                'subscription_id': $('#post_ID').val(),
                'update': update
            }
        });

        $.post(ajaxurl, data, function(response) {
            response = JSON.parse(response);
            if(response.display == true)
            {
                $('.wgc_sync_container').show();
            } else {
                if (update == true){
                    $('#wpwrap').css('opacity', '0.5');
                    window.location.href = window.location.href;
                    return false;
                }
            }
        });
    }
    $('body').on('click', '.wgc_sync', function(e) {
        e.preventDefault();
        update_status(true);
    });
    update_status(false);
});
