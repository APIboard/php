<?php

namespace Tests\Builders;

use Apiboard\OpenAPI\Structure\Parameter;

class ParameterBuilder extends Builder
{
    protected array $data = [
        'in' => 'query',
        'name' => 'Parameter',
    ];

    public function query(string $name): self
    {
        $this->data['in'] = 'query';
        $this->data['name'] = $name;

        return $this;
    }

    public function path(string $name): self
    {
        $this->data['in'] = 'path';
        $this->data['name'] = $name;

        return $this;
    }

    public function header(string $name): self
    {
        $this->data['in'] = 'header';
        $this->data['name'] = $name;

        return $this;
    }

    public function deprecated(bool $deprecated = true): self
    {
        $this->data['deprecated'] = $deprecated;

        return $this;
    }

    public function make(): Parameter
    {
        return new Parameter($this->data);
    }
}
