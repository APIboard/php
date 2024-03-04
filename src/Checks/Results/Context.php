<?php

namespace Apiboard\Checks\Results;

use Apiboard\Api;
use Apiboard\Checks\Check;
use Apiboard\OpenAPI\Endpoint;

class Context
{
    protected Api $api;

    protected ?Endpoint $endpoint;

    protected array $results = [];

    public function __construct(Api $api, ?Endpoint $endpoint)
    {
        $this->api = $api;
        $this->endpoint = $endpoint;
    }

    public function api(): Api
    {
        return $this->api;
    }

    public function endpoint(): ?Endpoint
    {
        return $this->endpoint;
    }

    public function addResult(Check $check, array $data, array $hash = []): void
    {
        $this->results[] = new Result($this->api, $check, $data, $hash);
    }

    /**
     * @return array<array-key,Result>
     */
    public function results(): array
    {
        return $this->results;
    }
}
