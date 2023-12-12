<?php

namespace Apiboard\Http;

use Apiboard\OpenAPI\Endpoint;
use Apiboard\OpenAPI\Structure\Schema;
use ArrayObject;

class ResponseData extends ArrayObject
{
    protected ?Endpoint $endpoint;

    protected ?Schema $schema;

    public function __construct(object|array $data, ?Endpoint $endpoint = null, ?Schema $schema = null)
    {
        $this->endpoint = $endpoint;
        $this->schema = $schema;
        parent::__construct($data, ArrayObject::ARRAY_AS_PROPS);
    }

    public function endpoint(): ?Endpoint
    {
        return $this->endpoint;
    }

    public function schema(): ?Schema
    {
        return $this->schema;
    }

    public function offsetGet(mixed $key): mixed
    {
        $value = parent::offsetGet($key);

        /** @var ?Schema $schema */
        $schema = $this->schema()?->properties()[$key] ?? null;

        if ($schema?->deprecated()) {
            $this->endpoint->api()->log()->deprecatedSchemaUsed($this->endpoint, $schema);
        }

        if (is_object($value) || is_array($value)) {
            return new self($value, $this->endpoint, $schema);
        }

        return $value;
    }
}
