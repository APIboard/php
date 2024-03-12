<?php

namespace Apiboard\OpenAPI;

use Apiboard\OpenAPI\Concerns\MatchesStrings;
use Apiboard\OpenAPI\Structure\Operation;
use Apiboard\OpenAPI\Structure\Parameters;
use Apiboard\OpenAPI\Structure\PathItem;
use Apiboard\OpenAPI\Structure\Servers;
use JsonSerializable;
use Psr\Http\Message\RequestInterface;

class Endpoint implements JsonSerializable
{
    use MatchesStrings;

    protected ?Servers $servers;

    protected PathItem $path;

    protected Operation $operation;

    public function __construct(?Servers $servers, PathItem $path, Operation $operation)
    {
        $this->servers = $servers;
        $this->path = $path;
        $this->operation = $operation;
    }

    public function servers(): ?Servers
    {
        return $this->servers;
    }

    public function path(): PathItem
    {
        return $this->path;
    }

    public function operation(): Operation
    {
        return $this->operation;
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

    public function matches(RequestInterface $request): bool
    {
        $usesHttpMethod = $this->matchingHttpMethod(
            $this->operation()->method(),
            $request->getMethod(),
        );

        if ($usesHttpMethod) {
            $request = $request->withUri($request->getUri()->withQuery(''));

            foreach ($this->servers() ?? [] as $server) {
                $usedOnServer = $this->matchingUriPattern(
                    $server->url().$this->path()->uri(),
                    $request->getUri()->__toString(),
                );

                if ($usedOnServer) {
                    return true;
                }
            }

            return $this->matchingUriPattern($this->path()->uri(), $request->getUri()->__toString());
        }

        return false;
    }

    public function jsonSerialize(): array
    {
        return [
            'servers' => $this->servers?->jsonSerialize(),
            'uri' => $this->path->uri(),
            'method' => $this->operation->method(),
            'path' => $this->path->jsonSerialize(),
            'operation' => $this->operation->jsonSerialize(),
        ];
    }
}
