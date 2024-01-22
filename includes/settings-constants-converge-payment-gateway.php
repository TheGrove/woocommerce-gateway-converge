<?php

/**
 * Constants related to settings for Converge2 Gateway.
 */

defined( 'ABSPATH' ) || exit;

define( 'WGC_KEY_ENABLED', 'enabled' );
define( 'WGC_SETTING_ENABLED_NO', 'no' );

define( 'WGC_KEY_ENVIRONMENT', 'environment' );
define( 'WGC_KEY_TITLE', 'title' );
define( 'WGC_KEY_TITLE_DEFAULT', 'Elavon Converge EU Gateway' );
define( 'WGC_KEY_TITLE_MAXLENGTH', 64 );
define( 'WGC_KEY_SAVE_FOR_LATER_USE_MESSAGE_MAXLENGTH', 255 );
define( 'WGC_SETTING_ENV_PRODUCTION', 'production' );
define( 'WGC_SETTING_ENV_DEV1', 'dev1' );
define( 'WGC_SETTING_ENV_QA1', 'qa1' );
define( 'WGC_SETTING_ENV_QA2', 'qa2' );
define( 'WGC_SETTING_ENV_QA3', 'qa3' );
define( 'WGC_SETTING_ENV_QA4', 'qa4' );
define( 'WGC_SETTING_ENV_SANDBOX', 'sandbox' );

define( 'WGC_KEY_DEBUG', 'debug' );
define( 'WGC_SETTING_DEBUG_NO', 'no' );

define( 'WGC_KEY_PUBLIC_KEY', 'public_key' );
define( 'WGC_KEY_SECRET_KEY', 'secret_key' );
define( 'WGC_KEY_MERCHANT_ALIAS', 'merchant_alias' );

define( 'WGC_KEY_CONVERGE_EMAIL', 'converge_email' );
define( 'WGC_SETTING_CONVERGE_EMAIL_YES', true );
define( 'WGC_SETTING_CONVERGE_EMAIL_NO', false );

define( 'WGC_KEY_PROCESSOR_ACCOUNT_ID', 'processor_account_id' );
define( 'WGC_KEY_MERCHANT_NAME', 'merchant_name' );

define( 'WGC_KEY_PAYMENT_ACTION', 'payment_action' );
define( 'WGC_SETTING_PAYMENT_ACTION_AUTH_ONLY', 'auth_only' );
define( 'WGC_SETTING_PAYMENT_ACTION_CAPTURE', 'capture' );

define( 'WGC_KEY_SHOPPER_STATEMENT', 'shopper_statement' );
define( 'WGC_KEY_NAME', 'name' );
define( 'WGC_KEY_PHONE', 'phone' );
define( 'WGC_KEY_URL', 'url' );

define( 'WGC_KEY_ENABLE_SAVE_PAYMENT_METHODS', 'enable_save_payment_methods' );
define( 'WGC_KEY_ENABLE_SAVE_PAYMENT_METHODS_YES', 'yes' );
define( 'WGC_KEY_ENABLE_SAVE_PAYMENT_METHODS_NO', 'no' );
define( 'WGC_KEY_SAVE_FOR_LATER_USE', 'wgc_save_for_later_use' );

define( 'WGC_KEY_ENABLE_SUBSCRIPTIONS_YES', 'yes' );
define( 'WGC_KEY_ENABLE_SUBSCRIPTIONS_NO', 'no' );

define( 'WGC_KEY_ENABLE_SUBSCRIPTIONS', 'enable_subscriptions' );
define( 'WGC_KEY_SUBSCRIPTIONS_DISCLOSURE_MESSAGE', 'subscriptions_disclosure_message' );
define( 'WGC_KEY_SUBSCRIPTIONS_DISCLOSURE_MESSAGE_MAXLENGTH', 255 );

define( 'WGC_KEY_SAVE_FOR_LATER_USE_MESSAGE', 'save_for_later_use_message' );

define( 'WGC_KEY_INTEGRATION_OPTION', 'integration_option' );
define( 'WGC_SETTING_INTEGRATION_HPP_REDIRECT', 'hpp_redirect' );
define( 'WGC_SETTING_INTEGRATION_HPP_POPUP', 'hpp_popup' );

define( 'WGC_KEY_VENDOR_ID', 'vendor_id' );
define( 'WGC_KEY_VENDOR_APP_NAME', 'vendor_app_name' );
define( 'WGC_KEY_VENDOR_APP_VERSION', 'vendor_app_version' );
define( 'WGC_KEY_PHP_VERSION', 'php_version' );
define( 'WGC_KEY_WC_VERSION', 'woocommerce_version' );

define( 'WGC_KEY_VENDOR_ID_VALUE', 'Elavon' );
define( 'WGC_KEY_VENDOR_APP_NAME_VALUE', 'WooCommerce Plugin' );
define( 'WGC_KEY_VENDOR_APP_VERSION_VALUE', WGC_VERSION );
define( 'WGC_KEY_VENDOR_CREATED_BY_VALUE', WGC_KEY_VENDOR_APP_NAME_VALUE  . ' v' . WGC_VERSION );

define( 'WGC_KEY_WOOCOMMERCE_ID', 'WooCommerceID' );

define( 'WGC_KEY_LICENSE_CODE', 'license_code' );

define( 'WGC_KEY_WC_ORDER_ID', 'wc_order_id' );
define( 'WGC_KEY_WC_C2_SHOPPER_ID', '_woocommerce_wgc_c2_shopper_id_%s' );
define( 'WGC_KEY_C2_ORDER_ID', 'c2_order_id' );
define( 'WGC_KEY_C2_PAYMENT_SESSION_ID', 'c2_payment_session_id' );
define( 'WGC_KEY_C2_HOSTED_CARD', 'c2_hosted_card' );

define( 'WGC_KEY_PROXY_SETTING', 'proxy_setting' );
define( 'WGC_KEY_USE_PROXY', 'use_proxy' );
define( 'WGC_SETTING_USE_PROXY_YES', true );
define( 'WGC_SETTING_USE_PROXY_NO', false );
define( 'WGC_KEY_PROXY_HOST', 'proxy_host' );
define( 'WGC_KEY_PROXY_PORT', 'proxy_port' );

define( 'WGC_PAYMENT_TOKEN_TYPE', 'Gateway_Converge_StoredCard' );
define( 'WGC_MAX_STORED_CARDS', 10 );

define( 'WGC_SUBSCRIPTION_NAME', 'converge-subscription' );
define( 'WGC_VARIABLE_SUBSCRIPTION_NAME', 'converge-variable-subscription' );
define( 'WGC_SUBSCRIPTION_VARIATION_NAME', 'converge-subscription-variation' );
define( 'WGC_SUBSCRIPTION_POST_TYPE', 'wgc_subscription' );


if ( ! defined( 'WGC_SANDBOX_HPP_URL' ) ) {
	define( 'WGC_SANDBOX_HPP_URL', 'https://uat.hpp.converge.eu.elavonaws.com' );
}

if ( ! defined( 'WGC_SANDBOX_API_URL' ) ) {
	define( 'WGC_SANDBOX_API_URL', 'https://uat.api.converge.eu.elavonaws.com' );
}

if ( ! defined( 'WGC_SANDBOX_HPP_LIGHTBOX_SCRIPT_URL' ) ) {
	define( 'WGC_SANDBOX_HPP_LIGHTBOX_SCRIPT_URL', 'https://uat.hpp.converge.eu.elavonaws.com/client/index.js' );
}

if ( ! defined( 'WGC_PRODUCTION_HPP_URL' ) ) {
	define( 'WGC_PRODUCTION_HPP_URL', 'https://hpp.eu.convergepay.com' );
}

if ( ! defined( 'WGC_PRODUCTION_HPP_LIGHTBOX_SCRIPT_URL' ) ) {
	define( 'WGC_PRODUCTION_HPP_LIGHTBOX_SCRIPT_URL', 'https://hpp.eu.convergepay.com/client/index.js' );
}

if ( ! defined( 'WGC_API_URL_MAP' )) {
	define ( 'WGC_API_URL_MAP', array(
		'sandbox' => 'https://uat.api.converge.eu.elavonaws.com/',
		'dev1' => 'https://dev1.api.converge.eu.elavonaws.com/',
		'qa1' => 'https://qa1.api.converge.eu.elavonaws.com/',
		'qa2' => 'https://qa2.api.converge.eu.nonprod.elavonaws.com/',
		'qa3' => 'https://qa3.api.converge.eu.elavonaws.com/',
		'qa4' => 'https://qa4.api.converge.eu.elavonaws.com/',
		'production' => 'https://api.eu.convergepay.com/'
	));
}

if ( ! defined( 'WGC_HPP_URL_MAP' )) {
	define ( 'WGC_HPP_URL_MAP', array(
		'sandbox' => 'https://uat.hpp.converge.eu.elavonaws.com/',
		'dev1' => 'https://dev1.hpp.converge.eu.elavonaws.com/',
		'qa1' => 'https://qa1.hpp.converge.eu.elavonaws.com/',
		'qa2' => 'https://qa2.hpp.converge.eu.nonprod.elavonaws.com/',
		'qa3' => 'https://qa3.hpp.converge.eu.elavonaws.com/',
		'qa4' => 'https://qa4.hpp.converge.eu.elavonaws.com/',
		'production' => 'https://hpp.eu.convergepay.com/'
	));
}

if ( ! defined( 'WGC_LIGHTBOX_URL_MAP' )) {
	define ( 'WGC_LIGHTBOX_URL_MAP', array(
		'sandbox' => 'https://uat.hpp.converge.eu.elavonaws.com/client/index.js',
		'dev1' => 'https://dev1.hpp.converge.eu.elavonaws.com/client/index.js',
		'qa1' => 'https://qa1.hpp.converge.eu.elavonaws.com/client/index.js',
		'qa2' => 'https://qa2.hpp.converge.eu.nonprod.elavonaws.com/client/index.js',
		'qa3' => 'https://qa3.hpp.converge.eu.elavonaws.com/client/index.js',
		'qa4' => 'https://qa4.hpp.converge.eu.elavonaws.com/client/index.js',
		'production' => 'https://hpp.eu.convergepay.com/client/index.js'
	));
}
