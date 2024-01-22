jQuery(function ($) {
    var change_payment = {
        init: function(){

            //payment method change.
            $('input[name="payment_method"]').on('click', this.payment_gateway_selected );

            this.init_payment_methods();

        },
        init_payment_methods: function(){
            var payment_methods = $('input[name="payment_method"]');

            if(payment_methods.length === 1){
                $(payment_methods[0]).hide();
            }

            if(payment_methods.filter(':checked').length === 0){
                //choose the first payment method.
                $(payment_methods[0]).prop('checked', true);
            }

            payment_methods.filter(':checked').trigger('click');

        },
        payment_gateway_selected: function() {
            if ( $( '.payment_methods input.input-radio' ).length > 1 ) {
                var target_payment_box = $( 'div.payment_box.' + $( this ).attr( 'ID' ) );

                if ( $( this ).is( ':checked' ) && ! target_payment_box.is( ':visible' ) ) {
                    $( 'div.payment_box' ).filter( ':visible' ).slideUp( 250 );

                    if ( $( this ).is( ':checked' ) ) {
                        $( 'div.payment_box.' + $( this ).attr( 'ID' ) ).slideDown( 250 );
                    }
                }
            } else {
                $( 'div.payment_box' ).show();
            }

            if ( $( this ).data( 'order_button_text' ) ) {
                $( '#place_order' ).val( $( this ).data( 'order_button_text' ) );
            } else {
                $( '#place_order' ).val( $( '#place_order' ).data( 'value' ) );
            }
        },
    }
    change_payment.init();
});
