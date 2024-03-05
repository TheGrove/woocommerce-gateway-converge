<?php

/**
 * Woocommerce Elavon Converge EU Gateway -  bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.elavon.com
 * @since             1.0.0
 * @package           Woocommerce_Gateway_Converge
 *
 * @wordpress-plugin
 * Plugin Name:          WooCommerce Elavon Converge EU Gateway
 * Plugin URI:           https://developer-eu.elavon.com/docs/plugins
 * Description:          Receive credit card payments using Elavon Converge EU Gateway.
 * Version:              1.16.0
 * Author:               Elavon
 * Author URI:           http://www.elavon.com
 * License:              GPL-2.0+
 * License URI:          http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:          elavon-converge-gateway
 * WC tested up to:      8.6
 * WC requires at least: 8.4
 * Requires PHP:         7.4
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WGC_DIR_PATH', plugin_dir_path( __FILE__ ) );

// WC active check
require_once( WGC_DIR_PATH . 'includes/functions-wc-gateway-converge.php' );
if ( ! wgc_is_woocommerce_active() ) {
	return;
}

define( 'WGC_VERSION', '1.16.0' );
define( 'WGC_PAYMENT_NAME', "elavon-converge-gateway" );
define( 'WGC_MAIN_FILE', __FILE__ );

require_once __DIR__ . '/vendor/autoload.php';

include_once 'includes/settings-constants-converge-payment-gateway.php';
include_once 'includes/class-wc-gateway-converge-order-wrapper.php';
include_once 'includes/class-wc-gateway-converge-api.php';
include_once 'includes/class-wc-gateway-converge-admin-order-actions.php';
include_once 'includes/class-wc-gateway-converge-admin-order-converge-status.php';
include_once 'includes/validation/class-wc-validation-message.php';
include_once 'includes/validation/class-wc-checkout-input-validator.php';
include_once 'includes/validation/class-wc-config-validator.php';
include_once 'includes/class-wc-gateway-converge-response-log-handler.php';


add_action( 'plugins_loaded', 'init_woocommerce_gateway_converge' );
add_filter( 'woocommerce_payment_gateways', 'add_gateway_class_to_payment_methods' );
add_filter( 'plugin_action_links', 'add_settings_to_plugins_list', 10, 5 );
add_action( 'woocommerce_before_template_part', 'wgc_before_template_part', 10, 3 );
add_action( 'woocommerce_init', 'woocommerce_init', 10 );

function init_woocommerce_gateway_converge() {
	// Set up localisation.
	$text_domain = wgc_get_payment_name();
	$locale      = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
	$locale      = apply_filters( 'plugin_locale', $locale, $text_domain );

	unload_textdomain( $text_domain );
	load_textdomain( $text_domain, WP_LANG_DIR . '/woocommerge-gateway-converge/woocommerce-gateway-converge-' . $locale . '.mo' );
	load_plugin_textdomain( wgc_get_payment_name(), false, basename( WGC_DIR_PATH ) . '/i18n/languages' );

	require_once 'includes/class-wc-gateway-converge.php';
	require_once 'includes/class-wc-payment-token-gateway-converge-storedcard.php';
}

/**
 * Show admin notice when PHP version is lower than required.
 */
function elavon_converge_gateway_php_version_admin_notice() {
	?>
	<div class="notice notice-error">
		<p>
			<?php
			printf(
				/* translators: %s: required PHP version */
				esc_html__( 'The Woocommerce Elavon Converge EU Gateway plugin requires PHP %s. Please contact your host to update your PHP version.', 'elavon-converge-gateway' ),
				'7.4 or +'
			);
			?>
		</p>
	</div>
	<?php
}

function woocommerce_init(){
	if ( version_compare( phpversion(), '7.4', '<' ) ) {
		add_action( 'admin_notices', 'elavon_converge_gateway_php_version_admin_notice' );
		return;
	}

	// Fix the issues related to WP Sessions that only works for logged in users
	wgc_force_non_logged_user_wc_session();

	if ( wgc_subscriptions_active() ) {

		add_filter('woocommerce_available_payment_gateways', 'wgc_conditional_payment_gateways');

		if ( is_admin() ) {
			include_once 'includes/validation/class-wc-subscription-validation-message.php';
			include_once 'includes/validation/class-wc-plan-validator.php';
			include_once 'includes/admin/meta-boxes/class-wc-meta-box-wgc-subscription-data.php';
			include_once 'includes/admin/meta-boxes/class-wc-meta-box-wgc-coupon-data.php';
			include_once 'includes/admin/class-wgc-admin-subscription-listing.php';
		}
		include_once 'includes/subscriptions/wgc-hooks.php';
		include_once 'includes/subscriptions/class-wc-converge-subscription.php';
		include_once 'includes/subscriptions/class-wc-product-converge-subscription.php';
		include_once 'includes/subscriptions/class-wc-product-converge-variable-subscription.php';
		include_once 'includes/subscriptions/class-wc-product-converge-subscription-variation.php';
		include_once 'includes/class-wc-gateway-converge-subscription-post-types.php';
		include_once 'includes/subscriptions/class-wc-cart-converge-subscriptions.php';
		include_once 'includes/subscriptions/class-wc-checkout-converge-subscriptions.php';
		include_once 'includes/subscriptions/class-wgc-form-handler.php';
	}
}

function add_gateway_class_to_payment_methods( $methods ) {
	$methods[] = 'WC_Gateway_Converge';

	return $methods;
}


function add_settings_to_plugins_list( $actions, $plugin_file ) {
	static $plugin;

	if ( ! isset( $plugin ) ) {
		$plugin = plugin_basename( __FILE__ );
	}
	if ( $plugin == $plugin_file ) {
		$page     = admin_url( sprintf( 'admin.php?page=wc-settings&tab=checkout&section=%s', WGC_PAYMENT_NAME ) );
		$settings = array( 'settings' => '<a href=' . $page . '>' . __( 'Settings', 'elavon-converge-gateway' ) . '</a>' );
		$actions  = array_merge( $settings, $actions );
	}

	return $actions;
}

function wgc_before_template_part( $template_name, $template_path, $located ) {
	if ( 'checkout/thankyou.php' == $template_name ) {
		woocommerce_output_all_notices();
	}
}
