<?php

namespace Apiboard;

use Apiboard\Checks\Checks;
use Apiboard\OpenAPI\Endpoint;
use Apiboard\OpenAPI\EndpointMatcher;
use Apiboard\OpenAPI\OpenAPI;
use Apiboard\OpenAPI\Structure\Document;
use Closure;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
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

    public function inspect(MessageInterface $message): void
    {
        $checks = new Checks($this, $message);

        if ($message instanceof RequestInterface) {
            $endpoint = $this->matchingEndpoint($message);

            if ($endpoint) {
                $checks->add(...$endpoint->checksFor($message));
            }
        }

        $this->runChecks($checks);
    }

    public function matchingEndpoint(RequestInterface $request): ?Endpoint
    {
        $endpoint = new EndpointMatcher($this->specification());

        return $endpoint->matchingIn($request);
    }

    public function runChecks(Checks $checks): void
    {
        ($this->checkRunner)($checks);
    }
}
