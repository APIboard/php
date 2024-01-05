<?php

namespace Apiboard\OpenAPI;

use Apiboard\Api;
use Apiboard\Checks\DeprecatedEndpoint;
use Apiboard\Checks\DeprecatedHeaderParameter;
use Apiboard\Checks\DeprecatedPathParameter;
use Apiboard\Checks\DeprecatedQueryParameter;
use Apiboard\Checks\DeprecatedRequestBody;
use Apiboard\OpenAPI\Structure\Operation;
use Apiboard\OpenAPI\Structure\Parameters;
use Apiboard\OpenAPI\Structure\PathItem;
use Apiboard\OpenAPI\Structure\RequestBody;
use Apiboard\OpenAPI\Structure\Responses;
use Apiboard\OpenAPI\Structure\Server;
use Psr\Http\Message\MessageInterface;

class Endpoint
{
    protected Api $api;

    protected ?Server $server;

    protected PathItem $path;

    protected Operation $operation;

    public function __construct(Api $api, ?Server $server, PathItem $path, Operation $operation)
    {
        $this->api = $api;
        $this->server = $server;
        $this->path = $path;
        $this->operation = $operation;
    }

    public function api(): Api
    {
        return $this->api;
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

    public function parameters(): Parameters
    {
        return new Parameters(array_merge(
            $this->path->parameters()?->toArray() ?? [],
            $this->operation->parameters()?->toArray() ?? [],
        ));
    }

    public function requestBody(): RequestBody
    {
        return $this->operation->requestBody();
    }

    public function responses(): Responses
    {
        return $this->operation->responses();
    }

    public function checksFor(MessageInterface $message): array
    {
        return array_map(
            fn (string $check) => new $check($message, $this),
            [
                DeprecatedEndpoint::class,
                DeprecatedHeaderParameter::class,
                DeprecatedPathParameter::class,
                DeprecatedQueryParameter::class,
                DeprecatedRequestBody::class,
            ],
        );
    }
}
