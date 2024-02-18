<?php

namespace Apiboard\OpenAPI;

use Apiboard\OpenAPI\Structure\Document;
use Apiboard\OpenAPI\Structure\Server;
use Apiboard\OpenAPI\Structure\Servers;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class EndpointMatcher
{
    protected Document $specification;

    public function __construct(Document $specification)
    {
        $this->specification = $specification;
    }

    public function matchingIn(RequestInterface $request): ?Endpoint
    {
        foreach ($this->specification->paths() as $path) {
            $operation = $path->operations()[strtolower($request->getMethod())] ?? null;

            if ($operation === null) {
                continue;
            }

            $server = $this->serverForUri(
                $operation->servers() ?? $path->servers() ?? $this->specification->servers(),
                $request->getUri(),
            );

            $endpoint = new Endpoint($server, $path, $operation);

            if ($endpoint->matches($request)) {
                return $endpoint;
            }
        }

        return null;
    }

    protected function serverForUri(?Servers $servers, UriInterface $uri): ?Server
    {
        if ($servers === null) {
            return null;
        }

        foreach ($servers as $server) {
            if (str_contains((string) $uri, $server->url())) {
                return $server;
            }
        }

        return null;
    }
}
