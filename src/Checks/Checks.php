<?php

namespace Apiboard\Checks;

use Apiboard\Api;
use Apiboard\Checks\Results\Context;
use Apiboard\Logging\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Checks
{
    protected Api $api;

    protected Logger $logger;

    protected RequestInterface $request;

    protected ?ResponseInterface $response;

    /**
     * @var array<array-key,Check>
     */
    protected array $checks = [];

    public function __construct(
        Api $api,
        Logger $logger,
        RequestInterface $request,
        ?ResponseInterface $response,
    ) {
        $this->api = $api;
        $this->logger = $logger;
        $this->request = $request;
        $this->response = $response;
    }

    public function api(): Api
    {
        return $this->api;
    }

    public function request(): RequestInterface
    {
        return $this->request;
    }

    public function response(): ?ResponseInterface
    {
        return $this->response;
    }

    public function add(Check ...$checks): self
    {
        foreach ($checks as $check) {
            $this->checks[] = $check;
        }

        return $this;
    }

    public function __invoke(): void
    {
        $endpoint = $this->api->matchingEndpoint($this->request);

        $context = new Context($this->api(), $endpoint);

        foreach ($this->checks as $check) {
            $this->callIfExists($check, 'request');
            $this->callIfExists($check, 'response');

            $check->run($context);
        }

        $this->logger->process($context);
    }

    protected function callIfExists(Check $check, string $method): void
    {
        if (method_exists($check, $method)) {
            $check->{$method}($this->{$method});
        }
    }
}
