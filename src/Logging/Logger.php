<?php

namespace Apiboard\Logging;

use Apiboard\OpenAPI\Endpoint;
use Apiboard\OpenAPI\Structure\Header;
use Apiboard\OpenAPI\Structure\MediaType;
use Apiboard\OpenAPI\Structure\Parameter;
use Apiboard\OpenAPI\Structure\Schema;
use Psr\Log\LoggerInterface;

class Logger
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function deprecatedEndpointUsed(Endpoint $endpoint): void
    {
        $this->log($endpoint, 'Deprecated endpoint was used.');
    }

    public function deprecatedParameterUsed(Endpoint $endpoint, Parameter $parameter): void
    {
        $this->log($endpoint, "Deprecated {$parameter->in()} parameter was used.", [
            'parameter' => $parameter->toArray(),
        ]);
    }

    public function deprecatedRequestBodyUsed(Endpoint $endpoint, MediaType $mediaType): void
    {
        $this->log($endpoint, "Deprecated {$mediaType->contentType()} request body schema was used.", [
            'media_type' => $mediaType->toArray(),
        ]);
    }

    public function deprecatedHeaderUsed(Endpoint $endpoint, Header $header): void
    {
        $this->log($endpoint, "Deprecated header[{$header->name()}] was used on response.", [
            'header' => $header->toArray(),
        ]);
    }

    public function deprecatedSchemaUsed(Endpoint $endpoint, Schema $schema): void
    {
        $types = implode('|', $schema->types()?->toArray() ?? []);

        $this->log($endpoint, "Deprecated {$types} schema was used.", [
            'schema' => $schema->toArray(),
        ]);
    }

    protected function log(Endpoint $endpoint, string $message, array $context = []): void
    {
        $context = array_merge([
            'api' => $endpoint->api()->id(),
            'operation' => [
                'method' => $endpoint->method(),
                'url' => $endpoint->url(),
            ],
        ], $context);

        $this->logger->warning($message, $context);
    }
}
