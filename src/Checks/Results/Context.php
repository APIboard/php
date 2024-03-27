<?php

namespace Apiboard\Checks\Results;

use Apiboard\Api;
use Apiboard\Checks\Concerns\NormalisesArrays;
use Apiboard\OpenAPI\Endpoint;

class Context
{
    use NormalisesArrays;

    protected Api $api;

    protected ?Endpoint $endpoint;

    protected array $results = [];

    public function __construct(Api $api, ?Endpoint $endpoint)
    {
        $this->api = $api;
        $this->endpoint = $endpoint;
    }

    public function id(): string
    {
        $normalisedEndpoint = json_encode(
            $this->normaliseArray(
                $this->endpoint()?->jsonSerialize() ?? [],
                'title',
                'description',
                'summary',
                'tags',
                'operationId',
                'requestBody',
                'externalDocs',
                'example',
                'examples',
                'schema',
                'parameters',
                'x-*',
                '$*',
            ),
        );

        return md5("{$this->api()->id()}:{$normalisedEndpoint}");
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
}
