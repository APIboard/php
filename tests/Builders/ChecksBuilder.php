<?php

namespace Tests\Builders;

use Apiboard\Api;
use Apiboard\Checks\Checks;
use Apiboard\Logging\Logger;
use Apiboard\Logging\NullLogger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ChecksBuilder extends Builder
{
    protected ?Api $api = null;

    protected ?Logger $logger = null;

    protected ?RequestInterface $request = null;

    protected ?ResponseInterface $response = null;

    public function logger(Logger $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function request(RequestInterface $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function response(ResponseInterface $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function make(): Checks
    {
        return new Checks(
            $this->api ?? ApiBuilder::new()->make(),
            $this->logger ?? new NullLogger(),
            $this->request ?? PsrRequestBuilder::new()->make(),
            $this->response,
        );
    }
}
