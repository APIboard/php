<?php

namespace Apiboard\OpenAPI;

use APIboard\Api;
use Apiboard\OpenAPI\Structure\Document;
use Apiboard\OpenAPI\Structure\Server;
use Apiboard\OpenAPI\Structure\Servers;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class EndpointMatcher
{
    protected Api $api;

    protected Document $specification;

    public function __construct(Api $api)
    {
        $this->api = $api;
        $this->specification = $api->specification();
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
                $request->getUri()
            );

            $uriPattern = $this->parseToPath(parse_url($server?->url() ?? '/')['path'] ?? '').$this->parseToPath($path->uri());
            $uriValue = $this->parseToPath($request->getUri()->getPath());

            $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $uriPattern);
            $pattern = "^$pattern$";

            if (preg_match("#$pattern#", $uriValue, $matches)) {
                return new Endpoint($this->api, $server, $path, $operation);
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

    protected function parseToPath(string $value): string
    {
        $value = ltrim($value, '/');

        if ($value === '') {
            return $value;
        }

        return '/'.trim($value, '/');
    }
}
