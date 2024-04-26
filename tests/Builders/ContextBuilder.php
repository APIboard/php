<?php

namespace Tests\Builders;

use Apiboard\Api;
use Apiboard\Checks\Results\Result;
use Apiboard\Context;
use Apiboard\OpenAPI\Endpoint;

class ContextBuilder extends Builder
{
    protected ?Api $api = null;

    protected ?Endpoint $endpoint = null;

    protected array $results = [];

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

    public function results(Result ...$results): self
    {
        foreach ($results as $result) {
            $this->results[] = $result;
        }

        return $this;
    }

    public function make(): Context
    {
        $context = new Context(
            $this->api ?? ApiBuilder::new()->make(),
            $this->endpoint,
        );

        $context->add(...$this->results);

        return $context;
    }
}
