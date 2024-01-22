<?php
/**
 * Settings for Converge2 Gateway.
 *
 */

defined( 'ABSPATH' ) || exit;

include_once 'settings-constants-converge-payment-gateway.php';

$default_title = __( WGC_KEY_TITLE_DEFAULT, 'elavon-converge-gateway' );

$prod_environments = array(
	WGC_SETTING_ENV_SANDBOX    => __( 'Sandbox', 'elavon-converge-gateway' ),
	WGC_SETTING_ENV_PRODUCTION => __( 'Production', 'elavon-converge-gateway' ),
);

$dev_environments = array(
	WGC_SETTING_ENV_SANDBOX    => __( 'Sandbox', 'elavon-converge-gateway' ),
	WGC_SETTING_ENV_DEV1   => __( 'dev1', 'elavon-converge-gateway' ),
	WGC_SETTING_ENV_QA1    => __( 'qa1', 'elavon-converge-gateway' ),
	WGC_SETTING_ENV_QA2    => __( 'qa2', 'elavon-converge-gateway' ),
	WGC_SETTING_ENV_QA3    => __( 'qa3', 'elavon-converge-gateway' ),
	WGC_SETTING_ENV_QA4    => __( 'qa4', 'elavon-converge-gateway' ),
	WGC_SETTING_ENV_PRODUCTION => __( 'Production', 'elavon-converge-gateway' ),
);

$environment_options = getenv('CSC_DEV_MODE') ? $dev_environments : $prod_environments;

return array(
	WGC_KEY_ENABLED     => array(
		'title'   => __( 'Enable/Disable', 'elavon-converge-gateway' ),
		'type'    => 'checkbox',
		/* translators: %1$s: payment gateway title, already translated */
		'label'   => sprintf(__( 'Enable %1$s', 'elavon-converge-gateway' ), $default_title),
		'default' => WGC_SETTING_ENABLED_NO,
	),

	WGC_KEY_ENVIRONMENT => array(
		'title'       => __( 'Environment', 'elavon-converge-gateway' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'desc_tip' => __( 'Choose environment.', 'elavon-converge-gateway' ),
		'default'     => WGC_SETTING_ENV_SANDBOX,
		'options'     => $environment_options,
	),

	WGC_KEY_TITLE => array(
		'title'    => __( 'Title', 'elavon-converge-gateway' ),
		'type'     => 'text',
		'desc_tip' => __( 'Payment method title that the customer will see during checkout.', 'elavon-converge-gateway' ),
		'default'  => $default_title,
		'arg_options'       => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),

	WGC_KEY_DEBUG       => array(
		'title'       => __( 'Debug Log', 'elavon-converge-gateway' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'elavon-converge-gateway' ),
		'default'     => WGC_SETTING_DEBUG_NO,
		/* translators: %s: URL */
		'description' => sprintf( __( 'Log Converge events inside %s . Use only for development purposes.', 'elavon-converge-gateway' ), '<code><a href='.admin_url('/admin.php?page=wc-status&tab=logs').'>' . WC_Log_Handler_File::get_log_file_path( wgc_get_payment_name() ) . '</a></code>' )
	),
	WGC_KEY_PROCESSOR_ACCOUNT_ID  => array(
		'title'       => __( 'Processor Account Id', 'elavon-converge-gateway' ),
		'type'        => 'text',
		'custom_attributes' => array( 'required' => true, ),
		'description'       => __( 'The processor account ID is used to identify the Merchant when connecting to Converge.',
			'elavon-converge-gateway' ),
		'desc_tip'          => true,
		'arg_options'       => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),
	WGC_KEY_MERCHANT_NAME => array(
		'title'             => __( 'Merchant Name', 'elavon-converge-gateway' ),
		'type'              => 'text',
		'description'       => __( 'The Merchant Name represents the Merchant "Doing Business As" (DBA) name and is automatically filled based on the Processor Account Id.', 'elavon-converge-gateway' ),
		'desc_tip'          => true,
		'disabled'          => true,
		'arg_options' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),
	WGC_KEY_MERCHANT_ALIAS  => array(
		'title'       => __( 'Merchant Alias', 'elavon-converge-gateway' ),
		'type'        => 'text',
		'description' => __( 'The merchant alias is an unique ID that acts as a username for authentication.', 'elavon-converge-gateway' ),
		'desc_tip'    => true,
		'arg_options' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),
	WGC_KEY_PUBLIC_KEY  => array(
		'title'       => __( 'Public Key', 'elavon-converge-gateway' ),
		'type'        => 'text',
		'description' => __( 'The public key for your account, provided by Converge.', 'elavon-converge-gateway' ),
		'desc_tip'    => true,
		'arg_options' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),

	WGC_KEY_SECRET_KEY => array(
		'title'       => __( 'Secret Key', 'elavon-converge-gateway' ),
		'type'        => 'password',
		'description' => __( 'The secret key for your account, provided by Converge.', 'elavon-converge-gateway' ),
		'desc_tip'    => true,
		'arg_options' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),
	WGC_KEY_PAYMENT_ACTION => array(
		'title'       => __( 'Payment Action', 'elavon-converge-gateway' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'elavon-converge-gateway' ),
		'default'     => 'sale',
		'desc_tip'    => true,
		'options'     => array(
			WGC_SETTING_PAYMENT_ACTION_CAPTURE          => __( 'Authorize and Immediate Capture', 'elavon-converge-gateway' ),
			WGC_SETTING_PAYMENT_ACTION_AUTH_ONLY => __( 'Authorize and Delayed Capture', 'elavon-converge-gateway' ),
		),
	),
	WGC_KEY_INTEGRATION_OPTION => array(
		'title'       => __( 'Integration Option', 'elavon-converge-gateway' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'desc_tip' => __( 'Choose the integration option.', 'elavon-converge-gateway' ),
		'default'     => 'redirect',
		'options'     => array(
			WGC_SETTING_INTEGRATION_HPP_REDIRECT => __( 'HPP (PCI SAQ A)', 'elavon-converge-gateway' ),
			WGC_SETTING_INTEGRATION_HPP_POPUP => __( 'Lightbox (PCI SAQ A)', 'elavon-converge-gateway' ),
		),
	),
	WGC_KEY_ENABLE_SAVE_PAYMENT_METHODS => array(
		'title'    => __( 'Enable Saved Payment Methods', 'elavon-converge-gateway' ),
		'type'     => 'checkbox',
		'desc_tip' => __( 'If enabled, the already saved payment methods and a checkbox for saving new payment methods will be available on the checkout page.', 'elavon-converge-gateway' ),
		'default'  => WGC_KEY_ENABLE_SAVE_PAYMENT_METHODS_NO,
	),
	WGC_KEY_SAVE_FOR_LATER_USE_MESSAGE => array(
		'title'             => __( 'Save For Later Use Message', 'elavon-converge-gateway' ),
		'type'              => 'textarea',
		'custom_attributes' => array( 'required' => true, ),
		'description'       => __( 'This message will be displayed to the shopper next to the Save for later use option.',
			'elavon-converge-gateway' ),
		'desc_tip'          => true,
		'default'           =>  __( 'By placing your order, you agree with your card details being saved.', 'elavon-converge-gateway' ),
		'sanitize_callback' => 'htmlentities',
	),
	WGC_KEY_ENABLE_SUBSCRIPTIONS => array(
		'title' => __( 'Enable Subscriptions', 'elavon-converge-gateway' ),
		'type' => 'checkbox',
		'desc_tip' => __( 'If enabled, you can sell subscription products on your site. Also, when subscriptions are enabled, the option for saved payment methods will automatically be selected.',
			'elavon-converge-gateway' ),
		'default' => WGC_KEY_ENABLE_SUBSCRIPTIONS_NO,
	),
	WGC_KEY_SUBSCRIPTIONS_DISCLOSURE_MESSAGE => array(
		'title'             => __( 'Disclosure Message for Subscriptions', 'elavon-converge-gateway' ),
		'type'              => 'textarea',
		'custom_attributes' => array( 'required' => true, ),
		'description'       => __( 'This message will be displayed to the shopper next to the Place order button if the cart contains subscription products.',
			'elavon-converge-gateway' ),
		'desc_tip'          => true,
		'default'           =>  __( 'By placing your order, you agree to the recurring charges.', 'elavon-converge-gateway' ),
		'sanitize_callback' => 'htmlentities',
	),
	WGC_KEY_CONVERGE_EMAIL => array(
		'title'       => __( 'Converge Email', 'elavon-converge-gateway' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'desc_tip' => __( 'Choose if Converge should send emails to the customer.', 'elavon-converge-gateway' ),
		'default'     => WGC_SETTING_CONVERGE_EMAIL_NO,
		'options'     => array(
			WGC_SETTING_CONVERGE_EMAIL_NO  => __( 'No', 'elavon-converge-gateway' ),
			WGC_SETTING_CONVERGE_EMAIL_YES => __( 'Yes', 'elavon-converge-gateway' ),
		),
	),
	WGC_KEY_LICENSE_CODE  => array(
		'title'       => __( 'License Code', 'elavon-converge-gateway' ),
		'type'        => 'text',
		'arg_options' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),
	WGC_KEY_SHOPPER_STATEMENT => array(
		'title'       => __( 'Dynamic Descriptor Settings', 'elavon-converge-gateway' ),
		'type'        => 'title',
		'description' => __( "Your dynamic descriptor settings affect what appears on your customer's credit card statement.", 'elavon-converge-gateway' ),
	),
	WGC_KEY_NAME  => array(
		'title'       => __( 'Name', 'elavon-converge-gateway' ),
		'type'        => 'text',
		'description' => __( "The value in the business name field of a customer's statement.", 'elavon-converge-gateway' ),
		'desc_tip'    => true,
		'arg_options' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),
	WGC_KEY_PHONE  => array(
		'title'       => __( 'Phone', 'elavon-converge-gateway' ),
		'type'        => 'text',
		'description' => __( "The value in the phone number field of a customer's statement.", 'elavon-converge-gateway' ),
		'desc_tip'    => true,
		'arg_options' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),
	WGC_KEY_URL  => array(
		'title'       => __( 'URL', 'elavon-converge-gateway' ),
		'type'        => 'text',
		'description' => __( "The value in the URL/web address field of a customer's statement.", 'elavon-converge-gateway' ),
		'desc_tip'    => true,
		'arg_options' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),
	WGC_KEY_PROXY_SETTING => array(
		'title'       => __( 'Proxy Settings', 'elavon-converge-gateway' ),
		'type'        => 'title',
		'description' => __( 'If your system uses a proxy server to establish the connection between WooCommerce and Converge, set API Uses Proxy to “Yes” and complete the Proxy Host and Proxy Port fields.', 'elavon-converge-gateway' ),
	),
	WGC_KEY_USE_PROXY  => array(
		'title'       => __( 'API Uses Proxy', 'elavon-converge-gateway' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'default'     => WGC_SETTING_USE_PROXY_NO,
		'options'     => array(
			WGC_SETTING_USE_PROXY_NO  => __( 'No', 'elavon-converge-gateway' ),
			WGC_SETTING_USE_PROXY_YES => __( 'Yes', 'elavon-converge-gateway' ),
		),
	),
	WGC_KEY_PROXY_HOST  => array(
		'title'       => __( 'Proxy Host', 'elavon-converge-gateway' ),
		'type'        => 'text',
		'arg_options' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),
	WGC_KEY_PROXY_PORT  => array(
		'title'       => __( 'Proxy Port', 'elavon-converge-gateway' ),
		'type'        => 'text',
		'arg_options' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
	),

);
