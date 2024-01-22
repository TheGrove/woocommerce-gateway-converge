<?php

namespace Elavon\Converge2\Client;

/**
 * Converge2 Client Factory Interface.
 */
interface ClientFactoryInterface
{
    /**
     * Get a Client preconfigured with Converge2 settings.
     *
     * @param ClientConfigInterface $c2_config
     * @param array $client_config
     * @return ClientInterface
     */
    public function getClient(ClientConfigInterface $c2_config, array $client_config = array());
}
