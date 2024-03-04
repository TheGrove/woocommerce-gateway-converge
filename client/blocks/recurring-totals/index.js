/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { Panel, TotalsItem, TotalsWrapper } from '@woocommerce/blocks-checkout';
import { decodeEntities } from '@wordpress/html-entities';

/**
 * Internal dependencies
 */
import './style.scss';

const RecurringTotalItem = ({ type, items }) => {
	if (!items && !items.length) {
		return null;
	}

	let label = '';
	if (type === 'subtotal') {
		label = __('Subtotal', 'elavon-converge-gateway');
	} else if (type === 'discount') {
		label = __('Coupon', 'elavon-converge-gateway');
	} else if (type === 'shipping') {
		label = __('Shipping', 'elavon-converge-gateway');
	} else if (type === 'taxes') {
		label = __('Taxes', 'elavon-converge-gateway');
	} else if (type === 'total') {
		label = __('Total', 'elavon-converge-gateway');
	}

	const total = items?.map((item, index) => {
		return (
			<TotalsWrapper key={`${type}-${index}`}>
				<div className="wc-block-components-totals-item wgc-block-recurring-totals-item">
					<span className="wgc-block-recurring-totals-item__label">
						{index === 0 ? label : ''}
					</span>
					<span className="wgc-block-recurring-totals-item__value">
						<strong>{decodeEntities(item.price)} </strong>
						{decodeEntities(item.frequency)}
					</span>
				</div>
			</TotalsWrapper>
		);
	});
	return total;
};

/**
 * This component is responsible for rending recurring totals.
 * It has to be the highest level item directly inside the SlotFill
 * to receive properties passed from Cart and Checkout.
 *
 * extensions is data registered into `/cart` endpoint.
 *
 * @param {Object} props            Passed props from SlotFill to this component.
 * @param {Object} props.extensions data registered into `/cart` endpoint.
 * @param {Object} props.cart       cart endpoint data in readonly mode.
 */
export const SubscriptionsRecurringTotals = (props) => {
	const { extensions } = props;
	const recurringTotals =
		extensions['woocommerce-gateway-converge']?.totals || {};

	return (
		<div className="wgs-recurring-totals-panel">
			<TotalsItem
				className="wgs-recurring-totals-panel__title"
				label={__('Recurring Totals', 'elavon-converge-gateway')}
			/>
			<Panel
				className="wgs-recurring-totals-panel__details"
				initialOpen={true}
				title={__('Details', 'elavon-converge-gateway')}
			>
				{Object.keys(recurringTotals).map((key) => {
					if (!recurringTotals[key]?.length) {
						return null;
					}

					return (
						<RecurringTotalItem
							type={key}
							key={key}
							items={recurringTotals[key]}
						/>
					);
				})}
			</Panel>
		</div>
	);
};
