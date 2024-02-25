<?php

namespace Apiboard;

use Apiboard\OpenAPI\Endpoint;
use Apiboard\OpenAPI\EndpointMatcher;
use Apiboard\OpenAPI\OpenAPI;
use Apiboard\OpenAPI\Structure\Document;
use Psr\Http\Message\RequestInterface;

class Api
{
    protected string $name;

    protected string $openapi;

    protected ?Document $document = null;

    public function __construct(string $name, string $openapi)
    {
        $this->name = $name;
        $this->openapi = $openapi;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function openapi(): string
    {
        return $this->openapi;
    }

    public function specification(): Document
    {
        return $this->document ??= (new OpenAPI())->parse($this->openapi);
    }

    public function matchingEndpoint(RequestInterface $request): ?Endpoint
    {
        $endpoint = new EndpointMatcher($this->specification());

        return $endpoint->matchingIn($request);
    }
}
