/**
 * External dependencies
 */
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { registerPlugin } from '@wordpress/plugins';
import { ExperimentalOrderMeta } from '@woocommerce/blocks-checkout';

/**
 * Internal dependencies
 */
import { elavonConvergePaymentMethod } from './elavon-converge';
import { SubscriptionsRecurringTotals } from './recurring-totals';
import { registerFilters } from './filters';

// Register the payment method.
registerPaymentMethod(elavonConvergePaymentMethod);

/**
 * Render the recurring totals component.
 */
const render = () => {
	return (
		<>
			<ExperimentalOrderMeta>
				<SubscriptionsRecurringTotals />
			</ExperimentalOrderMeta>
		</>
	);
};

registerPlugin('woocommerce-gateway-converge', {
	render,
	scope: 'woocommerce-checkout',
});

// Register the filters to modify the cart item prices.
registerFilters();

// Save Timezone Cookie.
if (
	typeof navigator !== 'undefined' &&
	// eslint-disable-next-line no-undef
	navigator.cookieEnabled &&
	typeof Intl !== 'undefined'
) {
	document.cookie =
		'wgc_timezone=' +
		Intl.DateTimeFormat()?.resolvedOptions().timeZone +
		';path=/';
}
