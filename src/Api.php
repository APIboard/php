<?php

namespace Apiboard;

use Apiboard\OpenAPI\Endpoint;
use Apiboard\OpenAPI\EndpointMatcher;
use Apiboard\OpenAPI\OpenAPI;
use Apiboard\OpenAPI\Structure\Document;
use Psr\Http\Message\RequestInterface;

class Api
{
    protected string $id;

    protected string $openapi;

    protected ?Document $document = null;

    public function __construct(string $id, string $openapi)
    {
        $this->id = $id;
        $this->openapi = $openapi;
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
        return $this->document ??= (new OpenAPI)->parse($this->openapi);
    }

    public function matchingEndpoint(RequestInterface $request): ?Endpoint
    {
        $endpoint = new EndpointMatcher($this->specification());

        return $endpoint->matchingIn($request);
    }
}
