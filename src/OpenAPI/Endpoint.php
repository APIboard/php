<?php

namespace Apiboard\OpenAPI;

use Apiboard\OpenAPI\Structure\Operation;
use Apiboard\OpenAPI\Structure\Parameters;
use Apiboard\OpenAPI\Structure\PathItem;
use Apiboard\OpenAPI\Structure\Server;
use JsonSerializable;
use Psr\Http\Message\RequestInterface;

class Endpoint implements JsonSerializable
{
    protected ?Server $server;

    protected PathItem $path;

    protected Operation $operation;

    public function __construct(?Server $server, PathItem $path, Operation $operation)
    {
        $this->server = $server;
        $this->path = $path;
        $this->operation = $operation;
    }

    public function method(): string
    {
        return strtoupper($this->operation->method());
    }

    public function path(): PathItem
    {
        return $this->path;
    }

    public function url(): string
    {
        return $this->server?->url().$this->path->uri();
    }

    public function deprecated(): bool
    {
        return $this->operation->deprecated();
    }

    public function parameters(): ?Parameters
    {
        $parameters = array_merge(
            $this->path->parameters()?->toArray() ?? [],
            $this->operation->parameters()?->toArray() ?? [],
        );

        if (count($parameters)) {
            return new Parameters($parameters);
        }

        return null;
    }

    public function operation(): Operation
    {
        return $this->operation;
    }

    public function matches(RequestInterface $request): bool
    {
        if ($this->method() !== $request->getMethod()) {
            return false;
        }

        $request = $request->withUri($request->getUri()->withQuery(''));

        $endpointUrl = $this->url();
        $requestUrl = (string) $request->getUri();

        $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $endpointUrl);
        $pattern = "^$pattern$";

        return (bool) preg_match("#$pattern#", $requestUrl);
    }

    public function jsonSerialize(): array
    {
        return [
            'server' => $this->server?->jsonSerialize(),
            'path' => $this->path->jsonSerialize(),
            'method' => $this->operation->method(),
            'uri' => $this->path->uri(),
            'operation' => $this->operation->jsonSerialize(),
        ];
    }
}
