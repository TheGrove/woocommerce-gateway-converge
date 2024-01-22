<div class="options_group wc_wgc_options_group show_if_converge-variable-subscription show_if_converge-subscription">
    <div class="show_if_converge-subscription">

    <?php

	use Elavon\Converge2\DataObject\TimeUnit;

	woocommerce_wp_text_input(
		array(
			'label'             => __( 'Subscription Price', 'elavon-converge-gateway' ),
			'placeholder'       => '0.00',
			'id'                => 'wgc_plan_price',
			'name'              => 'wgc_plan_price',
			'type'              => 'number',
			'desc_tip'          => true,
			'value'             => get_post_meta( $post->ID, '_wgc_plan_price', true ),
			'description'       => __( 'The price that is billed for the subscription on the period and interval that you assign.',
				'elavon-converge-gateway'
			),
			'custom_attributes' => array( 'min' => 0, 'step' => '0.01' ),
		)
	);
	woocommerce_wp_checkbox(
		array(
			'label'       => __( 'Introductory Rate', 'elavon-converge-gateway' ),
			'id'          => 'wgc_plan_introductory_rate_cb',
			'name'        => 'wgc_plan_introductory_rate',
			'value'       => get_post_meta( $post->ID, '_wgc_plan_introductory_rate', true ),
			'desc_tip'    => true,
			'description' => __( 'Including an introductory rate sets a different subscription amount for the initial payment(s) of a plan.',
				'elavon-converge-gateway'
			),
		)
	);
	?>
    <div id="wgc_plan_introductory_rate_fields">
        <span class="wgc_plan_introductory_rate_fields_text">
		    <?php echo __( 'Bill', 'elavon-converge-gateway' ); ?>
        </span>
		<?php
		woocommerce_wp_text_input( array(
				'placeholder'       => '0.00',
				'type'              => 'number',
				'label'             => null,
				'id'                => 'wgc_plan_introductory_rate_amount',
				'name'              => 'wgc_plan_introductory_rate_amount',
				'class'             => 'wgc_plan_introductory_rate_text_field',
				'value'             => get_post_meta( $post->ID, '_wgc_plan_introductory_rate_amount', true ),
				'custom_attributes' => array( 'min' => 0.01, 'step' => '0.01' ),
			)
		); ?>
        <span class="wgc_plan_introductory_rate_fields_text">
		    <?php echo __( 'for the first', 'elavon-converge-gateway' ); ?>
        </span>
	    <?php
	    woocommerce_wp_text_input(
		    array(
			    'placeholder'       => __( 'Enter a number', 'elavon-converge-gateway' ),
			    'id'                => 'wgc_plan_introductory_rate_billing_periods',
			    'type'              => 'number',
			    'label'             => null,
			    'name'              => 'wgc_plan_introductory_rate_billing_periods',
			    'class'             => 'wgc_plan_introductory_rate_text_field',
			    'value'             => get_post_meta( $post->ID, '_wgc_plan_introductory_rate_billing_periods', true ),
			    'custom_attributes' => array( 'min' => 0 ),
		    )
	    );
	    ?> <span class="wgc_plan_introductory_rate_fields_text">
		    <?php echo __( 'billing periods', 'elavon-converge-gateway' ); ?>
        </span>
    </div>

    <div id="wgc_plan_billing_frequency_fields">
	    <?php
	    woocommerce_wp_select( array(
			    'id'      => 'wgc_plan_billing_frequency_select',
			    'name'    => 'wgc_plan_billing_frequency',
			    'label'   => __( 'Billing Frequency', 'elavon-converge-gateway' ),
			    'options' => array(
				    TimeUnit::DAY   => __( 'Daily', 'elavon-converge-gateway' ),
				    TimeUnit::WEEK  => __( 'Weekly', 'elavon-converge-gateway' ),
				    TimeUnit::MONTH => __( 'Monthly', 'elavon-converge-gateway' ),
				    TimeUnit::YEAR  => __( 'Yearly', 'elavon-converge-gateway' ),
			    ),
			    'value'   => get_post_meta( $post->ID, '_wgc_plan_billing_frequency', true ),
		    )
	    );
		?>
        <div id="wgc_plan_billing_frequency_fields_week_month">
        <span class="wgc_plan_billing_frequency_count_fields_text">
		    <?php echo __( 'Every', 'elavon-converge-gateway' ); ?>
        </span><?php
	        woocommerce_wp_select(
		        array(
			        'id'      => 'wgc_plan_billing_frequency_count',
			        'name'    => 'wgc_plan_billing_frequency_count',
			        'label'   => null,
			        'value'   => get_post_meta( $post->ID, '_wgc_plan_billing_frequency_count', true ),
			        'options' => array_combine( $r = range( 1, 6 ), $r ),
		        )
			); ?>
            <span id="wgc_plan_billing_frequency_count_field_week">
				<?php echo __( 'week(s)', 'elavon-converge-gateway' ); ?>
            </span>
            <span id="wgc_plan_billing_frequency_count_field_month">
				<?php echo __( 'month(s)', 'elavon-converge-gateway' ); ?>
            </span>
        </div>
    </div>
	<?php
	$billing_ending = get_post_meta( $post->ID, '_wgc_plan_billing_ending', true );
	woocommerce_wp_radio(
		array(
			'id'      => 'wgc_plan_billing_ending_radio',
			'name'    => 'wgc_plan_billing_ending',
			'value'   => $billing_ending ? $billing_ending : 'cancel_schedule',
			'label'   => __( 'Ending', 'elavon-converge-gateway' ),
			'options' => array(
				'cancel_schedule' => __( 'End when cancelled or scheduled', 'elavon-converge-gateway' ),
				'billing_periods' => __( 'End subscription after billing periods: ', 'elavon-converge-gateway' ),
			),
		) );
	?>
	<?php
	woocommerce_wp_text_input(
		array(
			'placeholder'       => __( 'Enter a number', 'elavon-converge-gateway' ),
			'id'                => 'wgc_plan_ending_billing_periods',
			'label'             => null,
			'type'              => 'number',
			'name'              => 'wgc_plan_ending_billing_periods',
			'value'             => get_post_meta( $post->ID, '_wgc_plan_ending_billing_periods', true ),
			'custom_attributes' => array( 'min' => 0 ),
		)
	);
	wp_nonce_field( 'woocommerce_save_data', 'woocommerce_meta_nonce' );
	?>
    </div>
</div>
