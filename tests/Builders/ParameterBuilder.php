<?php

namespace Tests\Builders;

use Apiboard\OpenAPI\Structure\Parameter;

class ParameterBuilder extends Builder
{
    protected array $data = [
        'in' => 'query',
        'name' => 'Parameter',
    ];

    public function make(): Parameter
    {
        return new Parameter($this->data);
    }
}
