<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\AbstractEnum;

final class Endpoint extends AbstractEnum
{
    const TRANSACTION = 'transactions';
    const ORDER = 'orders';
    const PAYMENT_SESSION = 'payment-sessions';
    const HOSTED_CARD = 'hosted-cards';
    const MERCHANT = 'merchants';
    const PROCESSOR_ACCOUNT = 'processor-accounts';
    const BATCH = 'batches';
    const SHOPPER = 'shoppers';
    const STORED_CARD = 'stored-cards';
    const PLAN = 'plans';
    const SUBSCRIPTION = 'subscriptions';
    const PAYMENT_LINK = 'payment-links';
    const FOREX_ADVICE = 'forex-advices';
    const WEBHOOK = 'webhooks';
    const SIGNER = 'signers';
    const NOTIFICATION = 'notifications';
}
