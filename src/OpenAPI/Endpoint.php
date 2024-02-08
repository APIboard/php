<?php

namespace Apiboard\OpenAPI;

use Apiboard\Checks\DeprecatedEndpoint;
use Apiboard\Checks\DeprecatedParameters;
use Apiboard\Checks\DeprecatedRequestBody;
use Apiboard\OpenAPI\Structure\Operation;
use Apiboard\OpenAPI\Structure\Parameters;
use Apiboard\OpenAPI\Structure\PathItem;
use Apiboard\OpenAPI\Structure\RequestBody;
use Apiboard\OpenAPI\Structure\Responses;
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

    public function requestBody(): ?RequestBody
    {
        return $this->operation->requestBody();
    }

    public function responses(): Responses
    {
        return $this->operation->responses();
    }

    /**
     * @return array<array-key,Check>
     */
    public function checksFor(MessageInterface $message): array
    {
        $checks = [];

        if ($message instanceof RequestInterface) {
            $checks[] = new DeprecatedEndpoint($this);

            if ($this->parameters()) {
                $checks[] = new DeprecatedParameters($this->parameters());
            }

            if ($this->requestBody()) {
                $checks[] = new DeprecatedRequestBody($this->requestBody());
            }
        }

        return $checks;
    }
}
