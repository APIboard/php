<?php

namespace Tests\Builders;

use Apiboard\Checks\Checks;
use Apiboard\OpenAPI\Endpoint;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ChecksBuilder extends Builder
{
    protected ?Endpoint $endpoint = null;

    protected ?LoggerInterface $logger = null;

    protected ?RequestInterface $request = null;

    protected ?ResponseInterface $response = null;

    public function logger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function make(): Checks
    {
        return new Checks(
            $this->endpoint ?? EndpointBuilder::new()->make(),
            $this->logger ?? new NullLogger(),
            $this->request ?? PsrRequestBuilder::new()->make(),
            $this->response ?? PsrResponseBuilder::new()->make(),
        );
    }
}
