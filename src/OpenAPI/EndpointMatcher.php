<?php

namespace Apiboard\OpenAPI;

use Apiboard\OpenAPI\Structure\Document;
use Psr\Http\Message\RequestInterface;

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

            $servers = $operation->servers() ?? $path->servers() ?? $this->specification->servers();

            $endpoint = new Endpoint($servers, $path, $operation);

            if ($endpoint->matches($request)) {
                return $endpoint;
            }
        }

        return null;
    }
}
