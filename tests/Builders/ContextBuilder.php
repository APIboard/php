<?php

namespace Tests\Builders;

use Apiboard\Api;
use Apiboard\Context;
use Apiboard\OpenAPI\Endpoint;

class ContextBuilder extends Builder
{
    protected ?Api $api = null;

    protected ?Endpoint $endpoint = null;

    public function api(Api $api): self
    {
        $this->api = $api;

        return $this;
    }

    public function endpoint(Endpoint $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function make(): Context
    {
        return new Context(
            $this->api ?? ApiBuilder::new()->make(),
            $this->endpoint,
        );
    }
}
