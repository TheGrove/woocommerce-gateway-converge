/**
 * External dependencies
 */
import { decodeEntities } from '@wordpress/html-entities';
import { __ } from '@wordpress/i18n';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';

/**
 * Internal dependencies
 */
import { PAYMENT_METHOD_NAME } from './constants';
import { getElavonConvergeServerData } from './utils';

const { description, title, showSaveOption, showSavedCards, supports } =
	getElavonConvergeServerData();

const Content = () => {
	return decodeEntities(description || '');
};

const Label = (props) => {
	const { PaymentMethodLabel } = props.components;
	return <PaymentMethodLabel text={decodeEntities(title)} />;
};

registerPaymentMethod({
	name: PAYMENT_METHOD_NAME,
	label: <Label />,
	ariaLabel: __('Elavon Converge payment method', 'elavon-converge-gateway'),
	canMakePayment: () => true,
	content: <Content />,
	edit: <Content />,
	supports: {
		// Use `false` as fallback values in case server provided configuration is missing.
		showSavedCards: showSavedCards || false,
		showSaveOption: showSaveOption || false,
		features: supports || [],
	},
});
