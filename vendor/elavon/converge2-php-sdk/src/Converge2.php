<?php

namespace Elavon\Converge2;

use Elavon\Converge2\Client\ClientConfigInterface;
use Elavon\Converge2\Client\ClientFactoryInterface;
use Elavon\Converge2\Client\ClientInterface;
use Elavon\Converge2\Client\Curl\CurlClientFactory;
use Elavon\Converge2\Handler\ResponseHandlerInterface;
use Elavon\Converge2\Operation\BatchOperationTrait;
use Elavon\Converge2\Operation\ForexAdviceOperationTrait;
use Elavon\Converge2\Operation\HostedCardOperationTrait;
use Elavon\Converge2\Operation\MerchantOperationTrait;
use Elavon\Converge2\Operation\NotificationOperationTrait;
use Elavon\Converge2\Operation\OrderOperationTrait;
use Elavon\Converge2\Operation\PaymentLinkOperationTrait;
use Elavon\Converge2\Operation\PaymentSessionOperationTrait;
use Elavon\Converge2\Operation\PlanOperationTrait;
use Elavon\Converge2\Operation\ProcessorAccountOperationTrait;
use Elavon\Converge2\Operation\ShopperOperationTrait;
use Elavon\Converge2\Operation\SignerOperationTrait;
use Elavon\Converge2\Operation\StoredCardOperationTrait;
use Elavon\Converge2\Operation\SubscriptionOperationTrait;
use Elavon\Converge2\Operation\TransactionOperationTrait;
use Elavon\Converge2\Operation\WebhookOperationTrait;
use Elavon\Converge2\Request\AbstractRequest;
use Elavon\Converge2\Response\HostedCardResponse;
use Elavon\Converge2\Response\ResponseInterface;

class Converge2
{
    const SDK_VERSION = '1.9.0';

    const NON_EXISTENT_CONVERGE_ID = '2346789bcdfghjkmpqrtvwxy';
    const ERR_CODE_NOT_FOUND = 404;
    const ERR_CODE_UNAUTHORIZED = 401;
    const ERR_CODE_FORBIDDEN = 403;

    use TransactionOperationTrait;
    use OrderOperationTrait;
    use PaymentSessionOperationTrait;
    use HostedCardOperationTrait;
    use MerchantOperationTrait;
    use ProcessorAccountOperationTrait;
    use BatchOperationTrait;
    use ShopperOperationTrait;
    use StoredCardOperationTrait;
    use PlanOperationTrait;
    use SubscriptionOperationTrait;
    use PaymentLinkOperationTrait;
    use ForexAdviceOperationTrait;
    use WebhookOperationTrait;
    use SignerOperationTrait;
    use NotificationOperationTrait;

    /** @var ClientConfigInterface */
    protected $c2Config;

    protected $clientConfig = array();

    /** @var ClientFactoryInterface */
    protected $clientFactory;

    /** @var ClientInterface */
    protected $client;

    public function __construct(
        ClientConfigInterface $c2_config,
        $client_config = array(),
        ClientFactoryInterface $client_factory = null
    ) {
        $this->c2Config = $c2_config;
        $this->clientConfig = $client_config;

        $this->clientFactory = $client_factory ? $client_factory : new CurlClientFactory();
        $this->client = $this->clientFactory->getClient($this->c2Config, $this->clientConfig);
    }

    public static function getSdkVersion() {
        return self::SDK_VERSION;
    }

    /**
     * Test connection to Converge2 by
     * requesting a non-existent hosted card with public key.
     *
     * @param ResponseHandlerInterface|null $handler
     * @param bool $reset
     * @return HostedCardResponse
     */
    protected function testConnection(ResponseHandlerInterface $handler = null, $reset = false)
    {
        static $response;

        if (!$response || $reset) {
            $response = $this->getHostedCardWithPublicKey(self::NON_EXISTENT_CONVERGE_ID);
            if ($handler) {
                $handler->handle(clone $response);
            }
        }

        return $response;
    }

    /**
     * @param ResponseHandlerInterface|null $handler
     * @param bool $reset - whether to force a new request
     * @return bool
     *
     * Returns true if we get a FORBIDDEN, UNAUTHORIZED, NOT FOUND or OK
     * when requesting a non-existent hosted card.
     */
    public function canConnect(ResponseHandlerInterface $handler = null, $reset = false)
    {
        $response = $this->testConnection($handler, $reset);
        $response_code = $response->getRawResponseStatusCode();

        return
            self::ERR_CODE_FORBIDDEN == $response_code
            || self::ERR_CODE_UNAUTHORIZED == $response_code
            || self::ERR_CODE_NOT_FOUND == $response_code
            || $response->isSuccess();
    }

    /**
     * @param ResponseHandlerInterface|null $handler
     * @param bool $reset
     * @return bool
     */
    public function isAuthWithPublicKeyValid(ResponseHandlerInterface $handler = null, $reset = false)
    {
        $response = $this->testConnection($handler, $reset);

        return
            self::ERR_CODE_FORBIDDEN == $response->getRawResponseStatusCode()
            || $response->isSuccess();
    }

    /**
     * @param ResponseHandlerInterface|null $handler
     * @param bool $reset
     * @return bool
     */
    public function isAuthWithSecretKeyValid(ResponseHandlerInterface $handler = null, $reset = false)
    {
        static $response;

        if (!$response || $reset) {
            $response = $this->getHostedCard(self::NON_EXISTENT_CONVERGE_ID);
            if ($handler) {
                $handler->handle(clone $response);
            }
        }

        return
            self::ERR_CODE_NOT_FOUND == $response->getRawResponseStatusCode()
            || $response->isSuccess();
    }

    /**
     * @param AbstractRequest $request
     * @return ResponseInterface
     */
    protected function sendAndMakeResponse(AbstractRequest $request)
    {
        return $this->client->sendRequestAndMakeResponse($request);
    }

    protected function castResponseAs($class, ResponseInterface $response)
    {
        return new $class($response);
    }
}
