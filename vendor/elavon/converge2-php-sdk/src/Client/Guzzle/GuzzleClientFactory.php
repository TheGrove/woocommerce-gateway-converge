<?php

namespace Elavon\Converge2\Client\Guzzle;

use Elavon\Converge2\Client\ClientConfigInterface;
use Elavon\Converge2\Client\ClientFactoryInterface;

class GuzzleClientFactory implements ClientFactoryInterface
{
    public function getClient(ClientConfigInterface $c2_config, array $client_config = array())
    {
        return new GuzzleClient($c2_config, $client_config);
    }
}