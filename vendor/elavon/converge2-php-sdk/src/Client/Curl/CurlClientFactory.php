<?php

namespace Elavon\Converge2\Client\Curl;

use Elavon\Converge2\Client\ClientConfigInterface;
use Elavon\Converge2\Client\ClientFactoryInterface;

class CurlClientFactory implements ClientFactoryInterface
{
    public function getClient(ClientConfigInterface $c2_config, array $client_config = array())
    {
        return new CurlClient($c2_config, $client_config);
    }
}