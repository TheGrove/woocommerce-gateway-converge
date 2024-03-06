/**
 * External dependencies
 */
import { getSetting } from '@woocommerce/settings';

/**
 * Internal dependencies
 */
import { PAYMENT_METHOD_ID } from './constants';

/**
 * Elavon Converge Gateway data comes from the server passed on a global object.
 */
export const getElavonConvergeServerData = () => {
	const elavonConvergeServerData = getSetting(
		`${PAYMENT_METHOD_ID}_data`,
		null
	);
	if (!elavonConvergeServerData) {
		throw new Error(
			'Elavon Converge Gateway initialization data is not available'
		);
	}
	return elavonConvergeServerData;
};
