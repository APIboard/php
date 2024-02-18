<?php

namespace Apiboard\OpenAPI;

use Apiboard\Checks\DeprecatedOperation;
use Apiboard\Checks\DeprecatedParameters;
use Apiboard\Checks\DeprecatedRequestBody;
use Apiboard\OpenAPI\Structure\Operation;
use Apiboard\OpenAPI\Structure\Parameters;
use Apiboard\OpenAPI\Structure\PathItem;
use Apiboard\OpenAPI\Structure\Server;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;

class Endpoint
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

    public function url(): string
    {
        return $this->server?->url() . $this->path->uri();
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

    /**
     * @return array<array-key,Check>
     */
    public function checksFor(MessageInterface $message): array
    {
        $checks = [];

        if ($message instanceof RequestInterface) {
            $checks[] = new DeprecatedOperation($this);

            if ($this->parameters()) {
                $checks[] = new DeprecatedParameters($this->parameters());
            }

            if ($this->operation->requestBody()) {
                $checks[] = new DeprecatedRequestBody($this->operation->requestBody());
            }
        }

        return $checks;
    }

    public function matches(RequestInterface $request): bool
    {
        if ($this->method() !== $request->getMethod()) {
            return false;
        }

        $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $this->url());
        $pattern = "^$pattern$";

        return (bool) preg_match("#$pattern#", $request->getUri());
    }
}
