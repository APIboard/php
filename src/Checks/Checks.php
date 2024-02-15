<?php

namespace Apiboard\Checks;

use Apiboard\OpenAPI\Endpoint;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Checks
{
    protected Endpoint $endpoint;

    protected LoggerInterface $logger;

    protected RequestInterface $request;

    protected ResponseInterface $response;

    /**
     * @var array<array-key,Check>
     */
    protected array $checks = [];

    public function __construct(
        Endpoint $endpoint,
        LoggerInterface $logger,
        RequestInterface $request,
        ResponseInterface $response,
    ) {
        $this->endpoint = $endpoint;
        $this->logger = $logger;
        $this->request = $request;
        $this->response = $response;
    }

    public function endpoint(): Endpoint
    {
        return $this->endpoint;
    }

    public function request(): RequestInterface
    {
        return $this->request;
    }

    public function response(): ResponseInterface
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
        foreach ($this->checks as $check) {
            $results = [
                ...$check->run($this->request),
                ...$check->run($this->response),
            ];

            foreach ($results as $result) {
                $this->logger->log($result->severity(), $result->summary(), [
                    'endpoint' => [
                        'method' => $this->endpoint->method(),
                        'url' => $this->endpoint()->url(),
                    ],
                    'check' => $check->id(),
                    'details' => $result->details(),
                ]);
            }
        }
    }
}
