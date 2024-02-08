<?php

namespace Tests\Builders;

use Apiboard\OpenAPI\Endpoint;
use Apiboard\OpenAPI\Structure\Operation;
use Apiboard\OpenAPI\Structure\PathItem;
use Apiboard\OpenAPI\Structure\Response;
use Apiboard\OpenAPI\Structure\Schema;
use Psr\Log\LoggerInterface;

class EndpointBuilder extends Builder
{
    protected ?LoggerInterface $logger = null;

    protected string $method = 'GET';

    protected string $uri = '/';

    protected array $path = [];

    protected array $operation = [];

    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function uri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function deprecated(bool $deprecated = true): self
    {
        $this->operation['deprecated'] = $deprecated;

        return $this;
    }

    public function deprecatedPathHeader(string $name): self
    {
        $this->path['parameters'][] = [
            'in' => 'header',
            'name' => $name,
            'deprecated' => true,
        ];

        return $this;
    }

    public function deprecatedOperationHeaderParameter(string $name): self
    {
        $this->operation['parameters'][] = [
            'in' => 'header',
            'name' => $name,
            'deprecated' => true,
        ];

        return $this;
    }

    public function deprecatedPathParameter(string $name): self
    {
        $this->path['parameters'][] = [
            'in' => 'path',
            'name' => $name,
            'deprecated' => true,
        ];

        return $this;
    }

    public function deprecatedPathQueryParameter(string $name): self
    {
        $this->path['parameters'][] = [
            'in' => 'query',
            'name' => $name,
            'deprecated' => true,
        ];

        return $this;
    }

    public function deprecatedOperationQueryParameter(string $name): self
    {
        $this->operation['parameters'][] = [
            'in' => 'query',
            'name' => $name,
            'deprecated' => true,
        ];

        return $this;
    }

    public function requestBody(string $contentType, Schema $schema): self
    {
        $this->operation['requestBody']['content'][$contentType] = [
            'schema' => $schema->toArray(),
        ];

        return $this;
    }

    public function responses(Response ...$responses): self
    {
        foreach ($responses as $response) {
            $this->operation['responses'][$response->statusCode()] = $response->jsonSerialize();
        }

        return $this;
    }

    public function make(): Endpoint
    {
        return new Endpoint(
            null,
            new PathItem($this->uri, $this->path),
            new Operation($this->method, $this->operation),
        );
    }
}
