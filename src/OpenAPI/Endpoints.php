<?php

namespace Apiboard\OpenAPI;

use Apiboard\OpenAPI\Concerns\CanBeUsedAsArray;
use Apiboard\OpenAPI\Structure\Operation;
use Apiboard\OpenAPI\Structure\PathItem;
use Apiboard\OpenAPI\Structure\Servers;
use ArrayAccess;
use Countable;
use Iterator;

abstract class Endpoints implements ArrayAccess, Countable, Iterator
{
    use CanBeUsedAsArray;

    protected array $data;

    public function __construct(array ...$endpoints)
    {
        foreach ($endpoints as $endpoint) {
            $this->data[] = new Endpoint(
                $endpoint['servers'] ? new Servers($endpoint['servers']) : null,
                new PathItem($endpoint['uri'], $endpoint['path']),
                new Operation($endpoint['method'], $endpoint['operation']),
            );
        }
    }

    public function offsetGet(mixed $offset): ?Endpoint
    {
        return $this->data[$offset] ?? null;
    }

    public function current(): Endpoint
    {
        return $this->iterator->current();
    }
}
