<?php

namespace Apiboard\Checks;

use Apiboard\OpenAPI\Endpoint;
use Psr\Http\Message\RequestInterface;

abstract class Check
{
    protected RequestInterface $request;

    protected Endpoint $endpoint;

    public function __construct(RequestInterface $request, Endpoint $endpoint)
    {
        $this->request = $request;
        $this->endpoint = $endpoint;
    }

    abstract public function __invoke(): void;
}
