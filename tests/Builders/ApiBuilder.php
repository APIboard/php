<?php

namespace Tests\Builders;

use Apiboard\Api;

class ApiBuilder extends Builder
{
    protected ?string $openapi = null;

    public function openapi(string $openapi): self
    {
        $this->openapi = $openapi;

        return $this;
    }

    public function make(): Api
    {
        return new Api(
            'example',
            $this->openapi ?? __DIR__.'/../__fixtures__/specification-example.json',
        );
    }
}
