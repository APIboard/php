<?php

namespace Apiboard\Checks;

use Apiboard\OpenAPI\Structure\Schema;
use Closure;

class DeprecatedRequestBody extends Check
{
    public function __invoke(): void
    {
        $mediaType = $this->endpoint->requestBody()->content()[$this->request->getHeaderLine('Content-Type')];

        $payload = match ($mediaType->contentType()) {
            'application/json' => json_decode($this->request->getBody()),
            default => $this->request->getBody(),
        };

        $this->checkPayloadForDeprecations($payload, $mediaType->schema(), function () use ($mediaType) {
            $this->endpoint->api()->log()->deprecatedRequestBodyUsed($this->endpoint, $mediaType);
        });
    }

    protected function checkPayloadForDeprecations(mixed $payload, Schema $schema, Closure $onDeprecationFound): void
    {
        if ($schema->deprecated()) {
            $onDeprecationFound();
        }

        if (is_object($payload) && $schema->types()->isObject()) {
            /** @var Schema $propertySchema */
            foreach ($schema->properties() as $property => $propertySchema) {
                if ($propertySchema->deprecated() === false) {
                    continue;
                }

                if (property_exists($payload, $property)) {
                    $onDeprecationFound();

                    $this->checkPayloadForDeprecations($payload->{$property}, $propertySchema, $onDeprecationFound);
                }
            }
        }

        if (is_array($payload) && $schema->types()->isArray()) {
            foreach ($payload as $payload) {
                $this->checkPayloadForDeprecations($payload, $schema->items(), $onDeprecationFound);
            }
        }
    }
}
