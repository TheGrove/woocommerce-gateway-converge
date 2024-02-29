/**
 * External dependencies
 */
import { decodeEntities } from '@wordpress/html-entities';
import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { PAYMENT_METHOD_NAME } from './constants';
import { getElavonConvergeServerData } from './utils';

const {
	description,
	title,
	showSaveOption,
	showSavedCards,
	supports,
	hasSubscriptionInCart,
	subscriptionDisclosure,
} = getElavonConvergeServerData();

const Content = (props) => {
	const { emitResponse, eventRegistration, shouldSavePayment, token } = props;
	const { onPaymentSetup } = eventRegistration;
	useEffect(() => {
		const unsubscribe = onPaymentSetup(() => {
			const paymentMethodData = {};
			if (
				shouldSavePayment ||
				(hasSubscriptionInCart && showSaveOption)
			) {
				paymentMethodData.wgc_save_for_later_use = '1';
			}
			if (token) {
				paymentMethodData[`${PAYMENT_METHOD_NAME}_stored_card`] = token;
			} else {
				paymentMethodData[`${PAYMENT_METHOD_NAME}_stored_card`] =
					`${PAYMENT_METHOD_NAME}_new_card`;
			}
			return {
				type: emitResponse.responseTypes.SUCCESS,
				meta: {
					paymentMethodData,
				},
			};
		});
		return unsubscribe;
	}, [
		emitResponse.responseTypes.SUCCESS,
		onPaymentSetup,
		shouldSavePayment,
		token,
	]);

	// If savedTokenComponent, we don't need to show the description.
	if (props.isSavedMethod) {
		return null;
	}

	return (
		<>
			<p>{decodeEntities(description || '')}</p>
			{!!showSaveOption && !!hasSubscriptionInCart && (
				<p style={{ fontSize: '0.875em' }}>
					{__(
						'Your info and card details will be saved. Subscriptions must be tied to your profile in order to process recurring payments.',
						'elavon-converge-gateway'
					)}
				</p>
			)}
			{!!hasSubscriptionInCart && (
				<p className="wgc_subscription_disclosure_message">
					{decodeEntities(subscriptionDisclosure || '')}
				</p>
			)}
		</>
	);
};

const Label = (props) => {
	const { PaymentMethodLabel } = props.components;
	return <PaymentMethodLabel text={decodeEntities(title)} />;
};

export const elavonConvergePaymentMethod = {
	name: PAYMENT_METHOD_NAME,
	label: <Label />,
	ariaLabel: __('Elavon Converge payment method', 'elavon-converge-gateway'),
	canMakePayment: () => true,
	content: <Content />,
	edit: <Content isEditor={true} />,
	savedTokenComponent: <Content isSavedMethod={true} />,
	supports: {
		// Use `false` as fallback values in case server provided configuration is missing.
		showSavedCards: showSavedCards || false,
		showSaveOption: (showSaveOption && !hasSubscriptionInCart) || false,
		features: supports || [],
	},
};
