<?php

namespace Apiboard\Checks\Results;

use Apiboard\Api;
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

    public function add(Result ...$results): void
    {
        foreach ($results as $result) {
            $this->results[] = $result;
        }
    }

    /**
     * @return array<array-key,Result>
     */
    public function results(): array
    {
        return $this->results;
    }

    public function hashForResult(Result $result): string
    {
        $details = json_encode($result->data());

        return md5("{$this->api->name()}:{$result->check()}:{$details}");
    }
}
