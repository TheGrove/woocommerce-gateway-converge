<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\AmountAndCurrency;
use Elavon\Converge2\DataObject\Card;
use Elavon\Converge2\DataObject\Contact;
use Elavon\Converge2\DataObject\DebtorAccount;
use Elavon\Converge2\DataObject\MarkupRateAnnotation;
use Elavon\Converge2\DataObject\RecurringType;
use Elavon\Converge2\DataObject\ShopperInteraction;
use Elavon\Converge2\DataObject\ShopperStatement;
use Elavon\Converge2\DataObject\Source;
use Elavon\Converge2\DataObject\ThreeDSecureV2;
use Elavon\Converge2\DataObject\TransactionState;
use Elavon\Converge2\DataObject\TransactionType;
use Elavon\Converge2\DataObject\VerificationResults;

interface TransactionInterface extends FailureInterface
{
    /**
     * @return string|null
     */
    public function getHref();

    /**
     * @return string|null
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @return string|null
     */
    public function getModifiedAt();

    /**
     * @return TransactionType|null
     */
    public function getType();

    /**
     * @return Source|null
     */
    public function getSource();

    /**
     * @return string|null
     */
    public function getMerchant();

    /**
     * @return string|null
     */
    public function getProcessorAccount();

    /**
     * @return AmountAndCurrency|null
     */
    public function getTotal();

    /**
     * @return string|null
     */
    public function getTotalAmount();

    /**
     * @return string|null
     */
    public function getTotalCurrencyCode();

    /**
     * @return AmountAndCurrency|null
     */
    public function getTotalRefunded();

    /**
     * @return string|null
     */
    public function getTotalRefundedAmount();

    /**
     * @return string|null
     */
    public function getTotalRefundedCurrencyCode();

    /**
     * @return AmountAndCurrency|null
     */
    public function getIssuerTotal();

    /**
     * @return string|null
     */
    public function getIssuerTotalAmount();

    /**
     * @return string|null
     */
    public function getIssuerTotalCurrencyCode();

    /**
     * @return string|null
     */
    public function getConversionRate();

    /**
     * @return string|null
     */
    public function getMarkupRate();

    /**
     * @return MarkupRateAnnotation|null
     */
    public function getMarkupRateAnnotation();

    /**
     * @return string|null
     */
    public function getForexAdvice();

    /**
     * @return string|null
     */
    public function getParentTransaction();

    /**
     * @return string|null
     */
    public function getDescription();

    /**
     * @return ShopperStatement|null
     */
    public function getShopperStatement();

    /**
     * @return DebtorAccount|null
     */
    public function getDebtorAccount();

    /**
     * @return string|null
     */
    public function getCustomReference();

    /**
     * @return string|null
     */
    public function getShopperReference();

    /**
     * @return string|null
     */
    public function getProcessorReference();

    /**
     * @return string|null
     */
    public function getIssuerReference();

    /**
     * @return string|null
     */
    public function getOrderReference();

    /**
     * @return ShopperInteraction|null
     */
    public function getShopperInteraction();

    /**
     * @return string|null
     */
    public function getShopper();

    /**
     * @return Contact|null
     */
    public function getShipTo();

    /**
     * @return string|null
     */
    public function getShopperEmailAddress();

    /**
     * @return string|null
     */
    public function getShopperIpAddress();

    /**
     * @return string|null
     */
    public function getShopperLanguageTag();

    /**
     * @return string|null
     */
    public function getShopperTimeZone();

    /**
     * @return string|null
     */
    public function getOrder();

    /**
     * @return string|null
     */
    public function getSubscription();

    /**
     * @return RecurringType|null
     */
    public function getRecurringType();

    /**
     * @return string|null
     */
    public function getPreviousRecurringTransaction();

    /**
     * @return Card|null
     */
    public function getCard();

    /**
     * @return string|null
     */
    public function getHostedCard();

    /**
     * @return string|null
     */
    public function getStoredCard();

    /**
     * @return string|null
     */
    public function getPaymentLink();

    /**
     * @return string|null
     */
    public function getPaymentSession();

    /**
     * @return ThreeDSecureV2|null
     */
    public function getThreeDSecure();

    /**
     * @return string|null
     */
    public function getCreatedBy();

    /**
     * @return \stdClass|null
     */
    public function getCustomFields();

    /**
     * @return bool|null
     */
    public function getIsHeldForReview();

    /**
     * @return bool|null
     */
    public function getDoCapture();

    /**
     * @return bool|null
     */
    public function getDoSendReceipt();

    /**
     * @return bool|null
     */
    public function getIsAuthorized();

    /**
     * @return string|null
     */
    public function getAuthorizationCode();

    /**
     * @return VerificationResults|null
     */
    public function getVerificationResults();

    /**
     * @return TransactionState|null
     */
    public function getState();

    /**
     * @return string|null
     */
    public function getBatch();

    /**
     * @return array|null
     */
    public function getRelatedTransactions();

    /**
     * @return array|null
     */
    public function getFailures();

    /**
     * @return array|null
     */
    public function getHistory();
}
