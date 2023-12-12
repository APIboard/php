<?php

namespace Apiboard\Http;

use Apiboard\OpenAPI\Endpoint;
use Apiboard\OpenAPI\Structure\Headers;
use ArrayObject;
use LogicException;

class HeaderData extends ArrayObject
{
    protected ?Endpoint $endpoint;

    protected ?Headers $headers;

    public function __construct(array $data, ?Endpoint $endpoint = null, ?Headers $headers = null)
    {
        $this->endpoint = $endpoint;
        $this->headers = $headers;
        parent::__construct($data, ArrayObject::STD_PROP_LIST);
    }

    public function offsetExists(mixed $offset): bool
    {
        $header = $this->headers[$offset] ?? null;

        if ($header?->deprecated()) {
            $this->endpoint->api()->log()->deprecatedHeaderUsed($this->endpoint, $header);
        }

        return parent::offsetExists($offset);
    }

    public function offsetGet(mixed $key): mixed
    {
        $header = $this->headers[$key] ?? null;

        if ($header?->deprecated()) {
            $this->endpoint->api()->log()->deprecatedHeaderUsed($this->endpoint, $header);
        }

        return parent::offsetGet($key);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new LogicException('Response headers data may not be mutated using array access.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new LogicException('Response headers data may not be mutated using array access.');
    }
}
