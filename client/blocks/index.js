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
