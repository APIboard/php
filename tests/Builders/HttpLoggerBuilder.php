<?php

namespace Tests\Builders;

use Apiboard\Logging\HttpLogger;
use GuzzleHttp\Psr7\HttpFactory;
use Psr\Http\Client\ClientInterface;

class HttpLoggerBuilder extends Builder
{
    public function make(ClientInterface $http): HttpLogger
    {
        $factory = new HttpFactory();

        return new HttpLogger('::token::', $http, $factory, $factory);
    }
}
