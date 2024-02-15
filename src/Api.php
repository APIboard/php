<?php

namespace Apiboard;

use Apiboard\Checks\Checks;
use Apiboard\OpenAPI\Endpoint;
use Apiboard\OpenAPI\EndpointMatcher;
use Apiboard\OpenAPI\OpenAPI;
use Apiboard\OpenAPI\Structure\Document;
use Closure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Api
{
    protected string $id;

    protected string $openapi;

    protected LoggerInterface $logger;

    protected Closure $checkRunner;

    protected ?Document $document = null;

    public function __construct(string $id, string $openapi, LoggerInterface $logger, Closure $checkRunner)
    {
        $this->id = $id;
        $this->openapi = $openapi;
        $this->logger = $logger;
        $this->checkRunner = $checkRunner;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function openapi(): string
    {
        return $this->openapi;
    }

    public function specification(): Document
    {
        return $this->document ??= (new OpenAPI())->parse($this->openapi);
    }

    public function logger(): LoggerInterface
    {
        return $this->logger;
    }

    public function inspect(RequestInterface $request, ResponseInterface $response): void
    {
        $endpoint = $this->matchingEndpoint($request);

        if ($endpoint) {
            $checks = new Checks($endpoint, $this->logger(), $request, $response);

            $checks->add(...$endpoint->checksFor($request));
            $checks->add(...$endpoint->checksFor($response));

            ($this->checkRunner)($checks);
        }
    }

    public function matchingEndpoint(RequestInterface $request): ?Endpoint
    {
        $endpoint = new EndpointMatcher($this->specification());

        return $endpoint->matchingIn($request);
    }
}
