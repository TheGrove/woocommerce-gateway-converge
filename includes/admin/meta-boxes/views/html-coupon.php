<?php

woocommerce_wp_select(
	array(
		'id'          => 'converge_subscription_type',
		'label'       => __( 'Converge subscription coupon type', 'elavon-converge-gateway' ),
		'description' => __( 'This option determines if the discount is applied to the initial payment or to the initial payment and recurring for a subscription product.', 'elavon-converge-gateway' ),
		'options'     => array(
			'single'         => __( 'First payment', 'elavon-converge-gateway' ),
		),
		'value'       => get_post_meta(
			$coupon->get_id(),
			'_converge_subscription_type',
			true
		),
	)
);
