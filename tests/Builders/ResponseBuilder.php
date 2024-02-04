<?php

namespace Tests\Builders;

use Apiboard\OpenAPI\Structure\Response;
use Apiboard\OpenAPI\Structure\Schema;

class ResponseBuilder extends Builder
{
    protected string $statusCode = '200';

    protected array $response = [];

    public function deprecatedHeader(string $name): self
    {
        $this->response['headers'][$name] = [
            'deprecated' => true,
        ];

        return $this;
    }

    public function status(int $status): self
    {
        $this->statusCode = (string) $status;

        return $this;
    }

    public function header(string $name): self
    {
        $this->response['headers'][$name] = [];

        return $this;
    }

    public function responseBody(string $contentType, ?Schema $schema = null): self
    {
        $this->response['content'][$contentType] = [
            'schema' => $schema?->toArray() ?? [],
        ];

        return $this;
    }

    public function make(): Response
    {
        return new Response($this->statusCode, $this->response);
    }
}
