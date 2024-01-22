# Converge 2 PHP SDK Documentation

## Introduction

This is the **PHP** SDK implementation of Converge2 API as documented on [here](https://dev.api.converge.eu.elavonaws.com/documentation/api). For an efficient reading, one needs to become familiar with Converge2 API documentation first.

## General description of main SDK classes

### Converge2 Facade

The entry point into the SDK is the `Elavon\Converge2\Converge2` class which acts as a facade. It exposes methods able to perform the operations on the resources provided by Converge2 API. The use of Converge2 facade is highly recommended.

Converge2 API uses Basic HTTP Authentication with Merchant Alias as username and either the Public Key or the Secret key as password. This means that merchant alias, the public and secret Converge2 API keys are the most important pieces of configuration that are needed by the HTTP Client. To this end, the SDK provides the `Elavon\Converge2\Client\ClientConfig` class implementing the `Elavon\Converge2\Client\ClientConfigInterface`.

### Example 1: Instantiating the Converge2 class

```$php
$c2_config = new ClientConfig();
$c2_config->setMerchantAlias('the merchant alias');
$c2_config->setPublicKey('the public key');
$c2_config->setSecretKey('the secret key');

$converge2 = new Converge2($c2_config);
```

The `Converge2` constructor accepts two more optional arguments: an array with configuration options for the HTTP client that is being used behind the scenes and an instance of `Elavon\Converge2\Client\ClientFactoryInterface` which is used to create the HTTP Client. By default, `Converge2` uses a custom CURL-based client. If Guzzle 6 is available, one can pass the `Elavon\Converge2\Client\Guzzle\GuzzleClientFactory` as third argument to the constructor of `Converge2`.

### Payload data builders

The Converge2 API operations carrying the most of the heavy-load, e.g. create sale transaction, expect a certain request payload in form of a json body. The SDK provides various data builders, in `Elavon\Converge2\Request\Payload` namespace. They are the recommended way to build up such payload data.

### Example 2: Building up the data payload for the Create Sale Transaction operation

```$php
use Elavon\Converge2\Request\Payload\TransactionDataBuilder;
use Elavon\Converge2\DataObject\TransactionType;
use Elavon\Converge2\DataObject\CurrencyCode;

$transaction_builder = new TransactionDataBuilder();

$transaction_builder->setType(Elavon\Converge2\DataObject\TransactionType::SALE);
$transaction_builder->setHostedCard('converge payment hosted card previously created');
$transaction_builder->setOrder('converge order id');
$transaction_builder->setTotal('20',  CurrencyCode::USD);
$transaction_builder->setDoCapture(true);

$payload_data = $transaction_builder->getData();
```

### Example 3: Initiating the Create Sale Transaction request

```$php
$response = $converge2->createSaleTransaction($payload_data);
```

### Converge2 Responses

In the example above `$response` is an object of class `Elavon\Converge2\Response\TransactionResponse` which extends the base `Elavon\Converge2\Response\Response` implementing `Elavon\Converge2\Response\ResponseInterface`.

### Converge2 Requests

In the same way, behind the scenes, when executing the create sale transaction request, the Converge2 facade sends a `Elavon\Converge2\Request\CreateTransactionRequest`, which implements `Elavon\Converge2\Request\RequestInterface`. However, there should be no real need to use them directly.

### Converge2 Data Objects

The SDK provides implementations of various Converge2 data structures and types in the `Elavon\Converge2\DataObject` namespace. Many of them are enumerations of values, e.g. `Elavon\Converge2\DataObject\TransactionState` enumerates all possible states of a Converge2 transaction. Enumerations are implemented by extending `Elavon\Converge2\DataObject\AbstractEnum`.

The other data object classes implement certain substructures that are found in a Converge2 response body. For example, `Elavon\Converge2\DataObject\ThreeDSecureV1` is a wrapper for the 3D Secure substructure of a HostedCard response. They all extend `Elavon\Converge2\DataObject\AbstractObject`.

## Going into more details

### Client Config

Besides setting merchant alias, secret and public keys, there are a few more methods that can be used:

* `getApiVersion()`

Converge2 API recommends all requests to contain a header specifying which version of the API is to be used. At the moment there's only one version of the API which this method returns. There's no way to set the API version yet.


* `setSandboxMode()`, `setProductionMode()`, `isSandboxMode()`, `isProductionMode`

These methods allow for switching and checking for the active mode of the Client: sandbox or production.

`getSandboxBaseUri()`, `setSandboxBaseUri($uri)`, `getProductionBaseUri()`, `setProductionBaseUri($uri)`

Used to set the endpoints for the sandbox and production environments of Converge2 API. Normally, you'd not use these, as the SDK provides the working defaults.

* `getBaseUri()`

Returns Sandbox Base URI if sandbox mode is active, otherwise Production Base URI.

* `setPublicKey($public_key)`, `setSecretKey($secret_key)`

Set the public and secret keys.

* `setMerchantAlias($merchant_alias)`

Set the merchant alias.

* `setProxy($proxy)`

In case you are behind a proxy, this allows to configure the HTTP Client to use it.

```$php
$client_config->setProxy('127.0.0.1:3128');
```

* `setTimeout($timeout)`

Float describing the timeout of the request in seconds. Use 0 to wait indefinitely. Default set to 10 seconds.

### Data Objects extending `Elavon\Converge2\DataObject\AbstractEnum`

As mentioned, many of the Data Object classes are enumerations, which means they extend the ``Elavon\Converge2\DataObject\AbstractEnum`` class. If needed, you can get the actual value of a variable of enumeration type by calling on `getValue()` method.

Below only some examples are listed. There are more available. Please consult the API documentation. These map to what the API documentation references as Values.

#### `Elavon\Converge2\DataObject\TrueFalseOrUnknown`

Implements Converge2 TrueFalseOrUnknown enumeration.

Methods:

* `isTrue()` returns true if the value is 'true', false otherwise.

* `isFalse()` returns true if the value is 'false', false otherwise.

* `isUnknown()` returns true if the value is 'unknown', false otherwise.

#### `Elavon\Converge2\DataObject\TransactionState`

Implements Converge2 TransactionState enumeration type.

Methods:

* `isAuthorized()`, `isCaptured()`, `isDeclined()`, `isExpired()`, `isHeldForReview()`, `isSettled()`, `isSettlementDelayed()`, `isUnknown()`, `isVoided()`.

* `isRefundable()` returns true if transaction is in a state which in principle allows for it to be refunded.

* `isCapturable()` returns true if transaction is in a state which in principle allows for it to be captured.

* `isVoidable()` returns true if transaction is in a state which in principle allows for it to be voided.

#### `Elavon\Converge2\DataObject\TransactionType`

Enumerates all possible TransactionType values. Example: `TransactionType::SALE`.

Methods: `isSale()`, `isRefund()`, `isVoid()`.

#### `Elavon\Converge2\DataObject\OrderItemType`

Enumerates all possible OrderItemType values. Example: `OrderItemType::GOODS`.

Methods: `isGoods()`, `isService()`, `isTax()`, `isShipping()`, `isDiscount()`, `isUnknown()`.

#### `Elavon\Converge2\DataObject\BatchState`

Enumerates all possible BatchState values. Example: `BatchState::SETTLED`.

Methods: `isSubmitted()`, `isSettled()`, `isFailed()`, `isUnknown()`, `isRejected()`.

#### `Elavon\Converge2\DataObject\Verification`

Enumerates all possible Verification values. Example: `Verification::MATCHED`.

Methods: `isMatched()`, `isUnmatched()`, `isUnprovided()`, `isUnsupported()`, `isUnavailable()`, `isUnknown()`.

All enumeration types provide a `getValue()` method and also can be used in a string context.

Example:
```$php
$response = $converge2->createSaleTransaction($payload_data);
if ($resonse->isSuccess()) {
    $transaction_state_enum = $response->getState();
    $transaction_state_string = $transaction_state_enum->getValue();
    
    $string_context = 'The state is: ' . $transaction_state_enum;
}
```

#### `Elavon\Converge2\DataObject\ShopperInteraction`

Enumerates all possible ShopperInteraction values. Example: `ShopperInteraction::ECOMMERCE`.

Example:
```$php
$response = $converge2->getTransaction($id);
if ($resonse->isSuccess()) {
    $currency_string = $response->getTotalCurrencyCode();
    $shopper_interaction_string = $response->getShopperInteraction();
}
```

### Data Objects extending `Elavon\Converge2\DataObject\AbstractObject`

There other type of data objects are those extending `Elavon\Converge2\DataObject\AbstractDataObject`. These are essentially standard class objects that provide getter methods for each property.

Below only some examples are listed. There are more available. Please consult the API documentation. These map to what the API documentation references as Types.

#### `Elavon\Converge2\DataObject\Failure`

Implements the Converge2 API Failure type, which is found on failed responses from Converge2. Methods:

* `getCode()` returns string or null. Example value: 'unauthorized'.
* `getDescription()` returns string or null. Example value: 'A valid API key is required'.
* `getField()` returns string or null. Field name, if failure is linked to a specific field.

#### `Elavon\Converge2\DataObject\ThreeDSecureV1`

Implements the Converge2 API ThreeDSecureV1 type, which is found on hosted card responses from Converge2. Methods:

* `isSupported()` returns true if value is 'true', false if value is 'false' or 'unknown'.
* `isSuccessful()`  returns true if value is 'true', false if value is 'false' or 'unknown'.
* `getAccessControlServerUrl()` returns string or null.
* `getPayerAuthenticationRequest()` returns string or null.

Example:

```$php
$hosted_card = 'hosted_card id';
$response = $converge2->getHostedCard($hosted_card);

if ($response->isSuccess()) {
    $three_d_secure = $response->getThreeDSecureV1();
    if ($three_d_secure && $three_d_secure->isSupported()) {
        // Redirect to 3D Secure Authentication.
        // Get 3D Secure Payer Authentication Response.
        // This function call is not a function that the SDK provides.

        $pa_res = redirect_to_three_d_secure(
            $three_d_secure->getAccessControlServerUrl(),
            $three_d_secure->getPayerAuthenticationRequest()
        );

        $response2 = $converge2->updatePayerAuthenticationResponseInHostedCard($hosted_card, $pa_res);

        if ($response2->isSuccess()) {
            $three_d_secure = $response2->getThreeDSecureV1();
            if ($three_d_secure && $three_d_secure->isSuccessful()) {
                // Proceed with creating a sale transaction. Code is omitted.
            }
        }
    }
}
```

### Examples of some payload data builders

#### `Elavon\Converge2\Request\Payload\ContactDataBuilder`

```$php
$contact_builder = new ContactDataBuilder();

$contact_builder->setFullName('Alice Bobsawyer');
$contact_builder->setCompany('Acme');
$contact_builder->setStreet1('221 Baker St');
$contact_builder->setStreet2('Suite B');
$contact_builder->setCity('London');
$contact_builder->setRegion('England');
$contact_builder->setPostalCode('NW1 6XE');
$contact_builder->setCountryCode('GBR');
$contact_builder->setPrimaryPhone('+44 020 7946 0123');
$contact_builder->setAlternatePhone('+44 020 7946 0124');
$contact_builder->setFax('+44 020 7946 0125');
$contact_builder->setEmail('alice@email.com');
```

#### `Elavon\Converge2\Request\Payload\ShopperStatementDataBuilder`

This builder is rarely used by itself, because `TransactionDataBuilder` contains a helper method which hides its details.

```$php
$shopper_statement_builder = new ShopperStatementDataBuilder();

$shopper_statement_builder->setName('GLOBE THEATRE*OTHELLO');
$shopper_statement_builder->setPhone('02079021409');
$shopper_statement_builder->setUrl('GLOBE');
```

#### `Elavon\Converge2\Request\Payload\ThreeDSecureV1DataBuilder`

This builder is rarely used by itself, because `HostedCardDataBuilder` contains a helper method which hides its details.


```$php
$three_d_secure_builder = new ThreeDSecureV1DataBuilder();

$three_d_secure_builder->setPayerAuthenticationResponse('authentication response coming from 3D Secure');
```

#### `Elavon\Converge2\Request\Payload\HostedCardDataBuilder`

```$php
$hosted_card_builder = new HostedCardDataBuilder();

// rarely used in this form
$hosted_card_builder->setThreeDSecureV1($three_d_secure_builder->getData());

// rather use this helper method, since this is most often the only piece of data needed for 3D Secure
$hosted_card_builder->set3dsPayerAuthenticationResponse('authentication response coming from 3D Secure');
```

#### `Elavon\Converge2\Request\Payload\TotalDataBuilder`

This builder is rarely used by itself. Whenever an amount is needed, the parent data builders provide convenience setters. See for example `TransactionDataBuilder`.

```$php
$total_builder = new TotalDataBuilder();

$total_builder->setAmount('20.02');
$total_builder->setCurrencyCode(Elavon\Converge2\DataObject\CurrencyCode::GBP);
```

#### `Elavon\Converge2\Request\Payload\TransactionDataBuilder`

```$php
$transaction_builder = new TransactionDataBuilder();

$transaction_builder->setType(Elavon\Converge2\DataObject\TransactionType::SALE);
$transaction_data_builder->setHostedCard('converge payment hosted card previously created');

// Build the total first using TotalDataBuilder, then pass it down.
// $transaction_builder->setTotal($total_builder->getData());

// Instead of the above, here's a shortcut.
$transaction_builder->setTotalAmountCurrencyCode('20',  CurrencyCode::USD);

$transaction_builder->setDoCapture(true);
$transaction_builder->setDescription('February 2019 Rent');
$transaction_builder->setShopperInteraction(Elavon\Converge2\DataObject\ShopperInteraction::ECOMMERCE);
$transaction_builder->setShipTo($contact_builder->getData());
$transaction_builder->setBillTo($another_contact_builder->getData());
$transaction_builder->setShopperEmailAddress('alice@email.com');
$transaction_builder->setShopperIpAddress('10.9.234.22');
$transaction_builder->setTimeZone('Europe/London');
$transaction_builder->setLanguageTag('en-GB');

// Using ShopperStatementDataBuilder.
// $transaction_builder->setShopperStatement($shopper_statement_builder->getData());

// This is a shortcurt to the above.
$transaction_builder->setShopperStatementNamePhoneUrl(
  'GLOBE THEATRE*OTHELLO',
  '02079021409',
  'GLOBE'
);
$transaction_builder->setShopperReference('PO 4358');
$transaction_builder->setDoSendReceipt(false);
$transaction_builder->setCustomReference('Invoice 2017031602');
$transaction_builder->setCustomFields(array(
    'custom_field_1' => 'value1',
    'custom_field_2' => 'value2',
));

$transaction_builder->setCreatedBy('Converge EU WooCommerce Plugin');
$transaction_builder->setOrderReference('WooCommerce order id');
```

#### `Elavon\Converge2\Request\Payload\OrderItemDataBuilder`

```$php
$items = [];

$order_item_builder = new OrderItemDataBuilder();
$order_item_builder->setTotalAmountCurrencyCode('1', CurrencyCode::GBP);
$order_item_builder->setQuantity(10);
$order_item_builder->setUnitPriceAmountCurrencyCode('0.1', CurrencyCode::GBP);
$order_item_builder->setDescription('item 1');
$order_item_builder->setCustomReference('reference 1');
$order_item_builder->setType(OrderItemType::GOODS);

$items[] = $order_item_builder->getData();
```

#### `Elavon\Converge2\Request\Payload\OrderDataBuilder`

```$php
$order_builder = new OrderDataBuilder();
$order_builder->setTotalAmountCurrencyCode('36', CurrencyCode::GBP);
$order_builder->setDescription('Test order');
$order_builder->setItems($items);
$order_builder->setShipTo($contact_builder->getData());
$order_builder->setCustomReference('order reference');
$order_builder->setShopperEmailAddress('alice@email.com');
$order_builder->setShopperReference('PO 4358');
$order_builder->setCustomFields(['one' => '1', 'two' => '2']);
```

#### `Elavon\Converge2\Request\Payload\PaymentSessionDataBuilder`

```$php
$payment_session_builder = new PaymentSessionDataBuilder();

$payment_session_builder->setOrder('converge order id');
$payment_session_builder->setBillTo($contact_builder->getData());
$payment_session_builder->setReturnUrl('http://www.ecommerce.platform.com/return');
$payment_session_builder->setCancelUrl('http://www.ecommerce.platform.com/cancel');
$payment_session_builder->setOriginUrl('http://www.ecommerce.platform.com');
$payment_session_builder->setDefaultLanguageTag('en-GB');
$payment_session_builder->setCustomReference('reference');
$payment_session_builder->setCustomFields(['one' => '1', 'two' => '2']);

```

### Converge2 Responses

#### The base response

All Converge2 operations return an object implementing `Elavon\Converge2\Response\ResponseInterface`. It decorates an instance of `Elavon\Converge2\Client\Response\RawResponseInterface`, referenced as "raw response". Here are its methods:

* `isSuccess()` returns true if the response was considered successful. See below.
* `getShortErrorMessage()` returns null or a shorter description of the error that has occurred.
* `getRawErrorMessage()` return null or the complete description of the error that has occurred. May be the same as the short error message.
* `hasRawResponse()` returns true if the HTTP client actually provided a response object.
* `getRawResponse()` returns null or the Psr response object, i.e. an object which implements `Psr\Http\Message\ResponseInterface`.
* `getRawResponseBody()` returns null or the string body of the Psr response.
* `getRawResponseStatusCode()` returns null or the HTTP Protocol response code.
* `getData()` returns null or the response data as an object with some of its properties being other Data objects described in the Data objects section.
* `getException()` return null or the actual Exception object in case of a failure.

#### Responses extending the base response

Depending on the resource on which Converge2 operation is performed, the response is actually further extended to a more specific response class:

* `Elavon\Converge2\Response\TransactionResponse`

  * `getId()` returns null or the transaction id.
  * `getState()` returns null or an `Elavon\Converge2\DataObject\TransactionState` object.
  * `getDoCapture()` returns null or a boolean.
  * `getTotal()` returns null or stdClass.
  * `getTotalAmount()` returns null or numeric string.
  * `getTotalCurrencyCode()` returns null or string.
  * `getTotalRefunded()` returns null or stdClass.
  * `getTotalRefundedAmount()` returns null or numeric string.
  * `getTotalRefundedCurrencyCode()` returns null or string.
  * `getRelatedTransactions()` returns array of related transaction id strings.
  * `getCard()` returns null or an instance of `Elavon\Converge2\DataObject\Card`.
  
  There are many more getter functions available. Consult the API Documentation.

* `Elavon\Converge2\Response\TransactionPagedListResponse`

  * `getHref()` returns null or self url, the one triggering the response.
  * `getFirst()` returns null or the url to the first page of the list.
  * `getNext()` returns null or the url to the next page of the list.
  * `getPageToken` returns null or the current page token.
  * `getNextPageToken()` returns null or the next page token.
  * `getSize()` returns null or the number of items in the current page.
  * `getLimit()` returns null or the maximum items in a page.
  * `getItems()` returns null or an array of `Elavon\Converge2\DataObject\Resource\Transaction` objects.

* `Elavon\Converge2\Response\HostedCardResponse`

  * `getId()` returns null or the hosted card id.
  * `getThreeDSecureV1()` returns null or an `Elavon\Converge2\DataObject\ThreeDSecureV1` object.

* `Elavon\Converge2\Response\ProcessorAccountResponse`

   * `getTradeName()` returns null or a string.

* `Elavon\Converge2\Response\OrderResponse`

   * `getId()` returns null or a string.

There are more getters available on OrderResponse.

* `Elavon\Converge2\Response\OrderPagedListResponse`

  * `getHref()` returns null or self url, the one triggering the response.
  * `getFirst()` returns null or the url to the first page of the list.
  * `getNext()` returns null or the url to the next page of the list.
  * `getPageToken` returns null or the current page token.
  * `getNextPageToken()` returns null or the next page token.
  * `getSize()` returns null or the number of items in the current page.
  * `getLimit()` returns null or the maximum items in a page.
  * `getItems()` returns null or an array of `Elavon\Converge2\DataObject\Resource\Order` objects.

* `Elavon\Converge2\Response\MerchantResponse`

   * `getLegalName()` returns null or a string.

* `Elavon\Converge2\Response\PaymentSessionResponse`

   * `getId()` returns null or a string.
   
* `Elavon\Converge2\Response\BatchResponse`

   * `getId()` returns null or a string.
   
* `Elavon\Converge2\Response\BatchPagedListResponse`

  * `getHref()` returns null or self url, the one triggering the response.
  * `getFirst()` returns null or the url to the first page of the list.
  * `getNext()` returns null or the url to the next page of the list.
  * `getPageToken` returns null or the current page token.
  * `getNextPageToken()` returns null or the next page token.
  * `getSize()` returns null or the number of items in the current page.
  * `getLimit()` returns null or the maximum items in a page.
  * `getItems()` returns null or an array of `Elavon\Converge2\DataObject\Resource\Batch` objects.
  
* Similarly there are response types for each resource and when a resource has a list operation, then a paged list response for that resource.
   
#### When a response is considered a success?

The response from Converge2 is considered a success if all of the following apply:

* HTTP client returns a response with code strictly less than 400,
* The response data from Converge2 has no failures. This applies to TransactionResponses only, but may be subject to change.

### Converge2 Facade methods

* `canConnect($reset = false)`

Returns true if Converge2 is reachable.

* `isAuthWithPublicKeyValid($reset = false)`, `isAuthWithSecretKeyValid($reset = false)`

Returns true if the combination of merchant alias and public/secret key is valid, false otherwise.

Note: The above three functions statically hold the response object for the test call being made. This allows them to be called inexpensively multiple times. If you need to force a new request, pass `reset` as `true`.

#### Transaction operations

* `getTransaction($id)`

Fetches the transaction. Returns an `Elavon\Converge2\Response\TransactionResponse`.

```$php
$response = $converge2->getTransaction('transaction id');

if ($response->isSuccess()) {
    echo $response->getState()->getValue();
}
```

* `createSaleTransaction($data)`

Creates a sale transaction. Returns an `Elavon\Converge2\Response\TransactionResponse`.

```$php
$response = $converge2->createSaleTransaction($transaction_builder->getData());

if ($response->isSuccess()) {
    echo $response->getId();
}
```

* `captureTransaction($id)`

Updates a transaction by setting the `doCapture` field to true. Returns an `Elavon\Converge2\Response\TransactionResponse`. This is a specialized version of the call below.

* `updateTransaction($id, $data)`

```$php
$transaction_builder = new TransactionDataBuilder();
$transaction_builder->setDoCapture(true);
$response = $converge2->updateTransaction($id, $transaction_builder->getData());
```

Updates the data on the transaction with given id. Not all transaction fields are updatable. Please consult the API Documentation.

* `createVoidTransaction($data)`

Creates a void transaction which voids the parent transaction. Returns an `Elavon\Converge2\Response\TransactionResponse`.

```$php
$transaction_builder = new TransactionDataBuilder();
$transaction_builder->setType(TransactionType::VOID);
$transaction_builder->setParentTransaction('parent transaction id');

$response = $converge2->createVoidTransaction($transaction_builder->getData());
```

* `createRefundTransaction($data)`

Creates a refund transaction which refunds the parent transaction. Returns an `Elavon\Converge2\Response\TransactionResponse`.

```$php
$transaction_builder = new TransactionDataBuilder();
$transaction_builder->setType(TransactionType::REFUND);
$transaction_builder->setParentTransaction('parent transaction id');

// To partially refund.
// $transaction_builder->setTotalAmountCurrencyCode('20',  CurrencyCode::USD);

$response = $converge2->createRefundTransaction($transaction_builder->getData());
```

* `getTransactionList($query_str = '')`

Returns a `Elavon\Converge2\Response\TransactionPagedListResponse`. If this call is successful, you can fetch an array of `Elavon\Converge2\DataObject\Resource\Transaction` objects. Consult the API documentation for an understanding of what the optional `$query_str` could be. The example below should be of help as well.

```$php
$query_builder = new \Elavon\Converge2\Request\PagedListQuery\TransactionListQueryBuilder();
$query_builder->setLimit(3);
$query_builder->whereType()->isEqualTo(TransactionType::REFUND);
$query_builder
    ->whereCreatedAt()
    ->isLessThanOrEqualTo('2019-05-13T09:24:32.178Z')
    ->isGreaterThanOrEqualTo('2019-05-13T09:24:32.178Z');

$response = $converge2->getTransactionList($query_builder->getQueryString());

if ($response->isSuccess()) {
    foreach ($response->getItems() as $item) {
        echo $item->getId() . PHP_EOL;
    }
    
    $next_query_str = $query_builder->extractQueryStringFromUrl($response->getNext());
    $response = $converge2->getTransactionList($next_query_str);
}
``` 

#### HostedCard operations

* `getHostedCard($id)`

Fetches the hosted card. Returns `Elavon\Converge2\Response\HostedCardResponse`.

* `updateHostedCard($id, $data)`

Updates the hosted card. Returns `Elavon\Converge2\Response\HostedCardResponse`.

* `updatePayerAuthenticationResponseInHostedCard($id, $pa_res)`

A shortcut method to the above, since this is the main reason to ever update a hosted card.

See the section describing the `Elavon\Converge2\DataObject\ThreeDSecureV1` data object for an example of how these methods can be used.

* `createHostedCard($data)`

Creates a Converge 2 hosted card. Returns `Elavon\Converge2\Response\HostedCardResponse`

#### Processor Account operations

* `getProcessorAccount($id)`

Retrieves the processor account. Returns `Elavon\Converge2\Response\ProcessorAccountResponse`.

```$php
$response = $converge2->getProcessorAccount('processor account id');
if ($response->isSuccess()) {
  echo $response->getTradeName();
}
```

#### Order operations

* `createOrder($data)`

Creates a Converge2 order. Returns `Elavon\Converge2\Response\OrderResponse`.

```$php
$response = $converge2->createOrder($order_builder->getData());
if ($response->isSuccess()) {
  echo $response->getId();
}
```

* `getOrder($id)`

Fetches the order. Returns `Elavon\Converge2\Response\OrderResponse`.

* `updateOrder($id, $data)`

Updates the order. Returns `Elavon\Converge2\Response\OrderResponse`.

* `getOrderList($query_str = '')`

Returns a `Elavon\Converge2\Response\OrderPagedListResponse`. If this call is successful, you can fetch an array of `Elavon\Converge2\DataObject\Resource\Order` objects.

#### PaymentSession operations

* `createPaymentSession($data)`

Creates a Converge2 payment session. Returns `Elavon\Converge2\Response\PaymentSessionResponse`.

```$php
$response = $converge2->createPaymentSession($payment_session_builder->getData());
if ($response->isSuccess()) {
  echo $response->getId();
}
```

* `getPaymentSession($id)`

Retrieves the payment session data. Returns `Elavon\Converge2\Response\PaymentSessionResponse`.

```$php
$response = $converge2->getPaymentSession('payment session id');
if ($response->isSuccess()) {
  echo $response->getReturnUrl();
  echo $response->getCancelUrl();
  echo $response->getExpiresAt();
}
```

#### Merchant operations

* `getMerchant($id)`

Retrieves the merchant legal name under which the merchant operates. Returns `Elavon\Converge2\Response\MerchantResponse`.

```$php
$response = $converge2->getMerchant('merchant id');
if ($response->isSuccess()) {
  echo $response->getLegalName();
}
```
#### Batch operations

* `getBatch($id)`

Retrieves the batch data. Returns `Elavon\Converge2\Response\BatchResponse`.

```$php
$response = $converge2->getBatch('batch id');
if ($response->isSuccess()) {
  echo $response->getId();
  echo $response->getCreatedAt();
}
```

* `getBatchList($query_str = '')`

Returns a `Elavon\Converge2\Response\BatchPagedListResponse`. If this call is successful, you can fetch an array of `Elavon\Converge2\DataObject\Resource\Batch` objects. 

```$php
$query_builder = new \Elavon\Converge2\Request\PagedListQuery\EmptyFilterListQueryBuilder();
$query_builder->setLimit(3);

$response = $converge2->getBatchList($query_builder->getQueryString());

if ($response->isSuccess()) {
    foreach ($response->getItems() as $item) {
        echo $item->getId() . PHP_EOL;
    }
    
    $next_query_str = $query_builder->extractQueryStringFromUrl($response->getNext());
    $response = $converge2->getBatchList($next_query_str);
}
```

#### Shopper operations

* `createShopper($data)`

Creates the shopper. Returns `Elavon\Converge2\Response\ShopperResponse`.

* `getShopper($id)`

Retrieves the shopper data. Returns `Elavon\Converge2\Response\ShopperResponse`.

* `getShopperList($query_str = '')`

Returns a `Elavon\Converge2\Response\ShopperPagedListResponse`. If this call is successful, you can fetch an array of `Elavon\Converge2\DataObject\Resource\Shopper` objects. 

* `getShopperStoredCardList($shopper_id, $query_str = '')`

Returns a `Elavon\Converge2\Response\StoredCardPagedListResponse`. If this call is successful, you can fetch an array of `Elavon\Converge2\DataObject\Resource\StoredCard` objects. 

* `updateShopper($id, $data)`

Updates the shopper. Returns `Elavon\Converge2\Response\ShopperResponse`.

* `deleteShopper($id, $data)`

Deletes the shopper. Returns `Elavon\Converge2\Response\ShopperResponse`.

#### StoredCard operations

* `createStoredCard($data)`

Creates the stored card. Returns `Elavon\Converge2\Response\StoredCardResponse`.

* `getStoredCard($id)`

Retrieves the stored card data. Returns `Elavon\Converge2\Response\StoredCardResponse`.

* `updateStoredCard($id, $data)`

Updates the stored card. Returns `Elavon\Converge2\Response\StoredCarResponse`.

* `deleteStoredCard($id, $data)`

Deletes the stored card. Returns `Elavon\Converge2\Response\StoredCardResponse`.

#### Plan operations

* `createPlan($data)`

Creates the plan. Returns `Elavon\Converge2\Response\PlanResponse`.

* `getPlan($id)`

Retrieves the plan data. Returns `Elavon\Converge2\Response\PlanResponse`.

* `getPlanList($query_str = '')`

Returns a `Elavon\Converge2\Response\PlanPagedListResponse`. If this call is successful, you can fetch an array of `Elavon\Converge2\DataObject\Resource\Plan` objects. 

* `updatePlan($id, $data)`

Updates the plan. Returns `Elavon\Converge2\Response\PlanResponse`.

* `deletePlan($id, $data)`

Deletes the plan. Returns `Elavon\Converge2\Response\PlanResponse`.

#### Subscription operations

* `createSubscription($data)`

Creates the subscription. Returns `Elavon\Converge2\Response\SubscriptionResponse`.

* `getSubscription($id)`

Retrieves the subscription data. Returns `Elavon\Converge2\Response\SubscriptionResponse`.

* `getSubscriptionList($query_str = '')`

Returns a `Elavon\Converge2\Response\SubscriptionPagedListResponse`. If this call is successful, you can fetch an array of `Elavon\Converge2\DataObject\Resource\Subscription` objects. 

* `updateSubscription($id, $data)`

Updates the subscription. Returns `Elavon\Converge2\Response\SubscriptionResponse`.

#### PaymentLink operations

* `createPaymentLink($data)`

Creates the payment link. Returns `Elavon\Converge2\Response\PaymentLinkResponse`.

* `getPaymentLink($id)`

Retrieves the payment link data. Returns `Elavon\Converge2\Response\PaymentLinkResponse`.

* `updatePaymentLink($id, $data)`

Updates the payment link. Returns `Elavon\Converge2\Response\PaymentLinkResponse`.

#### ForexAdvice operations

* `createForexAdvice($data)`

Creates the forex advice. Returns `Elavon\Converge2\Response\ForexAdviceResponse`.

* `getForexAdvice($id)`

#### Webhook operations

* `createWebhook($data)`

Creates the webhook. Returns `Elavon\Converge2\Response\WebhookResponse`.

* `getWebhook($id)`

Retrieves the webhook data. Returns `Elavon\Converge2\Response\WebhookResponse`.

* `getWebhookList($query_str = '')`

Returns a `Elavon\Converge2\Response\WebhookPagedListResponse`. If this call is successful, you can fetch an array of `Elavon\Converge2\DataObject\Resource\Webhook` objects. 

* `getWebhookSignerList($webhook_id, $query_str = '')`

Returns a `Elavon\Converge2\Response\SignerPagedListResponse`. If this call is successful, you can fetch an array of `Elavon\Converge2\DataObject\Resource\Signer` objects. 

* `updateWebhook($id, $data)`

Updates the webhook. Returns `Elavon\Converge2\Response\WebhookResponse`.

* `deleteWebhook($id, $data)`

Deletes the webhook. Returns `Elavon\Converge2\Response\WebhookResponse`.

#### Signer operations

* `createSigner($data)`

Creates the signer. Returns `Elavon\Converge2\Response\SignerResponse`.

* `getSigner($id)`

Retrieves the signer data. Returns `Elavon\Converge2\Response\SignerResponse`.

* `deleteSigner($id, $data)`

Deletes the signer. Returns `Elavon\Converge2\Response\SignerResponse`.

#### Notification operations

* `getNotification($id)`

Retrieves the notification data. Returns `Elavon\Converge2\Response\NotificationResponse`.

* `getNotificationList($query_str = '')`

Returns a `Elavon\Converge2\Response\NotificationPagedListResponse`. If this call is successful, you can fetch an array of `Elavon\Converge2\DataObject\Resource\Notification` objects. 

### User input validation

The SDK also contains basic user input validation functionality. It offers validator classes with which one builds up a collection of constraints to be asserted on a given set of user input. The result of a validation is an array of objects implementing ViolationInterface. The clients of the SDK are then left to take care of how these violations are converted into user friendly error messages. This is because the SDK, purposely, does not offer any string translation functionality.

#### Constraints

Constraints implement the `Elavon\Converge2\Request\Payload\Validation\Constraint\ConstraintInterface`. They are the simplest units of validation. An easy way to create a new constraint is to extend the `Elavon\Converge2\Request\Payload\Validation\Constraint\AbstractConstraint` class. The SDK contains the following ready-made constraint classes:

* `Required()` - Asserts that a value is not null or empty string.
* `MaxLength($max)` - Asserts that a string is not longer than $max chars.
* `MinLength($min)` - Asserts that a string is not shorter than $min chars.
* `BasicSafeString()` - Asserts that a string matches the `BasicSafeString::PATTERN` regex pattern (see API documentation).
* `PhoneSafeString()` - Asserts that a string matches the `PhoneSafeString::PATTERN` regex pattern.

#### Violations

If a value does not fit into a constraint, the constraint will return an array of `Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\ViolationInterface`.

```$php

use Elavon\Converge2\Request\Payload\Validation\Constraint\Required;

$constraint = new Required();
$violations = $constraint->assert('');

foreach($violations as $v) {
    print_r($v);
    echo $v->getFormattedMessage();
}

/* Output
Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\Violation Object
(
    [violation:protected] => Array
        (
            [constraint] => maxLength
            [message] => Must be at most %d characters long
            [parameters] => Array
                (
                    [maxLength] => 3
                )

            [value] => abcd
            [field] =>
        )

)
Must be at most 3 characters long
*/
```

The `field` property of a violation will be explained later.

#### Constraint Collections

Constraints can be and are grouped together into `Elavon\Converge2\Request\Payload\Validation\Constraint\ConstraintCollectionInterface` collections. In the background, a collection is an associative array of constraints. For example:

```$php

use Elavon\Converge2\Request\Payload\Validation\Constraint\ConstraintCollection;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Required;
use Elavon\Converge2\Request\Payload\Validation\Constraint\MaxLength;
use Elavon\Converge2\Request\Payload\Validation\Constraint\BasicSafeString;
use Elavon\Converge2\Request\Payload\Validation\Constraint\PhoneSafeString;

// A constraint collection that is meant to assert that
// 'street_1' is required and no longer than 50 characters.
$constraint_collection = new ConstraintCollection();
$constraint_collection->add(new Required, 'street_1');
$constraint_collection->add(new MaxLength(50), 'street_1');

// This collection contains all the rules necessary
// to validate a Contact data structure as required by Converge 2 API.
$contact_constraint_collection = new ConstraintCollection();

$commonMaxLengthConstraint = new MaxLength(Converge2Schema::getInstance()->getCommonMaxLength()); // 255 long
$basicSafeStringConstraint = new BasicSafeString();
$phoneSafeStringConstraint = new PhoneSafeString();

foreach (
    array(
        C2ApiFieldName::FULL_NAME,
        C2ApiFieldName::COMPANY,
        C2ApiFieldName::STREET_1,
        C2ApiFieldName::STREET_2,
        C2ApiFieldName::CITY,
        C2ApiFieldName::REGION,
        C2ApiFieldName::POSTAL_CODE,
    )
    as $field
) {
    $contact_constraint_collection
        ->add($commonMaxLengthConstraint, $field)
        ->add($basicSafeStringConstraint, $field);
}

foreach (
    array(
        C2ApiFieldName::PRIMARY_PHONE,
        C2ApiFieldName::ALTERNATE_PHONE,
        C2ApiFieldName::FAX,
    )
    as $field
) {
    $contact_constraint_collection
        ->add($commonMaxLengthConstraint, $field)
        ->add($phoneSafeStringConstraint, $field);
}

```

#### Validators

Constraints and ConstraintCollections are not meant to be used directly. Instead `Elavon\Converge2\Request\Payload\Validation\ValidatorInterface` objects make use of them.

#### `Elavon\Converge2\Request\Payload\Validation\DataValidator`

This validator can validate data in associative arrays or objects extending `AbstractDataBuilder`. Here is an example where data from a `ContactDataBuilder` is validated. Note that not all the fields of a contact are mentioned, for brevity. See 'Examples of some payload data builders' section.

```$php

$contact_builder = new ContactDataBuilder();
$contact_builder->setFullName(str_repeat('Alice % Bobsawyer', 20));

$contact_validator = new DataValidator();

// $contact_constraint_collection was defined in the example above.
$contact_validator->setConstraintCollection($contact_constraint_collection);

$contact_validator->validate($contact_builder->getDataAsArrayAssoc());

// This would also work, but no other objects except those extending AbstractDataBuilder.
// $contact_validator->validate($contact_builder);

print_r($contact_validator->getViolations());
print_r($contact_validator->getErrorMessages());

/*
Output:

Array
(
    [0] => Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\Violation Object
        (
            [violation:protected] => Array
                (
                    [constraint] => maxLength
                    [message] => Must be at most %d characters long
                    [parameters] => Array
                        (
                            [maxLength] => 255
                        )

                    [value] => OMMITTED FOR BREVITY
                    [field] => fullName
                )

        )

    [1] => Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\Violation Object
        (
            [violation:protected] => Array
                (
                    [constraint] => basicSafeString
                    [message] => Must match the following pattern: %s
                    [parameters] => Array
                        (
                            [basicSafeString] => /^[^%<>\/\[\]{}\\]*$/
                        )

                    [value] => OMMITTED FOR BREVITY
                    [field] => fullName
                )

        )

)
Array
(
    [0] => fullName: Must be at most 255 characters long
    [1] => fullName: Must match the following pattern: /^[^%<>\/\[\]{}\\]*$/
)

*/

```

Notice how the `field` property of the violation objects has the value of 'fullName'. This is because the respective constraints were added under the 'fullName' key in `$contact_constraint_collection` and it so happens that `$contact_data_builder` does have a value associated to 'fullName' key.

#### `Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\Violation\ViolationRendererInterface`

Also notice that the error messages in the example above are not quite user friendly and also not translated. To this end the SDK offers the `ViolationRendererInterface`. Here's how it would be employed:

```$php

// Assume that the __($text) function returns a translated string.
function __($text)
{
    return $text;
}

class ExampleViolationRenderer implements ViolationRendererInterface
{
    protected $labels = array();

    public function __construct()
    {
        $this->labels['fullName'] = __('Full name');
    }

    protected function getLabel($field)
    {
        return isset($this->labels[$field]) ? $this->labels[$field] : $field;
    }

    public function toString(ViolationInterface $violation)
    {
        if ($violation->getConstraintId() == MaxLength::ID) {
            return sprintf(
                __('The field "%s" has %d characters, but needs to be at most %d characters long.'),
                $this->getLabel($violation->getField()),
                strlen($violation->getOffendingValue()),
                $violation->getConstraintParameter($violation->getConstraintId())
            );
        }

        // Fallback.
        return $violation->getFormattedMessage();
    }
}

$contact_validator = new DataValidator();
$contact_validator
    ->setConstraintCollection($contact_constraint_collection);
    ->setViolationRenderer(new ExampleViolationRenderer());

$contact_validator->validate($contact_builder->getDataAsArrayAssoc());

print_r($contact_validator->getErrorMessages());

/*
Output:

Array
(
    [0] => The field "Full name" has 340 characters, but needs to be at most 255 characters long.
    [1] => fullName: Must match the following pattern: /^[^%<>\/\[\]{}\\]*$/
)
*/

```

Note that the ViolationRenderer needs to be able to render any kind of possible violation not just one specific violation. The example above falls back on the default renderer for "unknown" violations.

#### `Elavon\Converge2\Request\Payload\Validation\ValueValidator`

The `ValueValidator` is useful for validating single values, like so:

```$php
$full_name_validator = new ValueValidator();
$full_name_validator
    ->maxLength(255)
    ->basicSafeString()
    ->field('fullName')
    ->validate(str_repeat('Alice % Bobsawyer', 20))
    ->getErrorMessage();
```

#### Advanced Constraints - `Elavon\Converge2\Request\Payload\Validation\Constraint\ValidatorConstraint`

This is a compound constraint that may help with validating deep associative arrays. `ValidatorConstraint` is a constraint that wraps a validator. Let's see an example:

```$php
$order_builder = new OrderDataBuilder();
$order_builder->setDescription('Test order');
$order_builder->setTotalAmountCurrencyCode('36', CurrencyCode::GBP);
$order_builder->setShipTo($contact_builder);

$order_validator = new DataValidator();
$order_validator
    ->maxLength(3, C2ApiFieldName::DESCRIPTION)
    ->addConstraint(
        new ValidatorConstraint($contact_validator),
        C2ApiFieldName::SHIP_TO
    )
    ->validate($order_builder);

print_r($order_validator->getErrorMessages());

/*
Output:

Array
(
    [0] => description: Must be at most 3 characters long
    [1] => shipTo.fullName: Must be at most 255 characters long
    [2] => shipTo.fullName: Must match the following pattern: /^[^%<>\/\[\]{}\\]*$/
)
*/
 
```

Notice how the field value for contact full name follows the path in the deep array: `shipTo.fullname`.

#### Advanced Constraints - `Elavon\Converge2\Request\Payload\Validation\Constraint\ForEachWithValidatorConstraint`

This compound constraint is very similar to the above, the difference being that it will iterate through all items on an associative array and apply the validator to each item. Example:

```$php
$another_contact_builder = clone $contact_builder;
$another_contact_builder->setFullName('Alice Bob');
$another_contact_builder->setCompany('%Company%');

$data = array(
    'contacts' => array(
        'contact1' => $contact_builder->getDataAsArrayAssoc(),
        'contact2' => $another_contact_builder->getDataAsArrayAssoc(),
    ),
);

$validator = new DataValidator();
$validator
    ->addConstraint(
        new ForEachWithValidatorConstraint($contact_validator),
        'contacts'
    )
    ->validate($data);

print_r($validator->getErrorMessages());

/*
Output:

Array
(
    [0] => contacts.contact1.fullName: Must be at most 255 characters long
    [1] => contacts.contact1.fullName: Must match the following pattern: /^[^%<>\/\[\]{}\\]*$/
    [2] => contacts.contact2.company: Must match the following pattern: /^[^%<>\/\[\]{}\\]*$/
)
*/

```

#### `Elavon\Converge2\Schema\Converge2Schema`

Validation may be performed in a complete custom way without using the validation functionality described above. Still, one might need to get hold of the restriction definitions that Converge 2 API has. These can be found in the Converge2Schema class.

`Elavon\Converge2\Schema\Converge2Schema` methods:

* `getShopperStatementNameMaxLength()` returns an integer.
* `getShopperStatementPhoneMaxLength()` returns an integer.
* `getShopperStatementUrlMaxLength()` returns an integer.
* `getShopperReferenceMaxLength()` returns an integer.
* `getOrderMaxItems()` returns an integer.
* `getContactFullNameMaxLength()` returns an integer.
* `getCommonConvergeMaxLength()` returns an integer.

Example:

```$php
use Elavon\Converge2\Schema\Converge2Schema;

$converge2_schema = Converge2Schema::getInstance();
if (strlen($user_input) > $converge2_schema->getShopperReferenceMaxLength()) {
    // Issue an error for the user to fix.
}
```

## Appendix A

### Key encryption

Applications using the SDK will most probably be storing the public and secret keys for example in a database and it is a good idea to store them encrypted. The SDK provides the utility encryption class, `Elavon\Converge2\Util\Encryption`, that can be used to encrypt and decrypt sensitive information. It requires the `ext-openssl` PHP extension and uses `AES-128-CBC` encryption method.

Example:

```$php
use Elavon\Converge2\Util\Encryption;

$encryption = new Encryption(md5('salt string', true))
$encrypted_key = $encryption->encryptCredential('secret key');

echo $encryption->decryptCredential($encrypted_key);
```