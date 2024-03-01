/**
 * External dependencies
 */
import { registerCheckoutFilters } from '@woocommerce/blocks-checkout';
import { decodeEntities } from '@wordpress/html-entities';

const modifySubtotalPriceFormat = (defaultValue, extensions) => {
	const billingFrequencyString =
		extensions['woocommerce-gateway-converge']
			?.billing_frequency_subtotal || '';
	if (billingFrequencyString) {
		return defaultValue + decodeEntities(billingFrequencyString);
	}
	return defaultValue;
};

const modifyCartItemPrice = (pricePlaceholder, extensions) => {
	const billingFrequencyString =
		extensions['woocommerce-gateway-converge']?.billing_frequency_total ||
		'';
	if (billingFrequencyString) {
		return pricePlaceholder + decodeEntities(billingFrequencyString);
	}
	return pricePlaceholder;
};

/**
 * This is the filter integration API, it uses registerCheckoutFilters
 * to register its filters, each filter is a key: function pair.
 * The key the filter name, and the function is the filter.
 *
 * Each filter function is passed the previous (or default) value in that filter
 * as the first parameter, the second parameter is a object of 3PD registered data.
 * Filters must return the previous value or a new value with the same type.
 * If an error is thrown, it would be visible for store managers only.
 */
export const registerFilters = () => {
	registerCheckoutFilters('woocommerce-gateway-converge', {
		subtotalPriceFormat: modifySubtotalPriceFormat,
		cartItemPrice: modifyCartItemPrice,
	});
};
