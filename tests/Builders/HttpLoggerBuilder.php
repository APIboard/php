<?php

namespace Tests\Builders;

use Apiboard\Logging\HttpLogger;
use Psr\Http\Client\ClientInterface;
use PsrDiscovery\Discover;

class HttpLoggerBuilder extends Builder
{
    public function make(ClientInterface $http): HttpLogger
    {
        return new HttpLogger(
            '::token::',
            $http,
            Discover::httpRequestFactory(),
            Discover::httpStreamFactory(),
        );
    }
}
