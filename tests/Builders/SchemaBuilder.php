<?php

namespace Tests\Builders;

use Apiboard\OpenAPI\Structure\Schema;

class SchemaBuilder extends Builder
{
    protected array $schema = [];

    public function type(string $type): self
    {
        $this->schema['type'] = $type;

        return $this;
    }

    public function property(string $name, Schema $schema): self
    {
        $this->schema['properties'][$name] = $schema->toArray();

        return $this;
    }

    public function items(Schema $schema): self
    {
        $this->schema['items'] = $schema->toArray();

        return $this;
    }

    public function deprecated(bool $deprecated = true): self
    {
        $this->schema['deprecated'] = $deprecated;

        return $this;
    }

    public function make(): Schema
    {
        return new Schema($this->schema);
    }
}
