<?php

namespace Apiboard\Checks;

use Apiboard\Checks\Concerns\AcceptsRequest;
use Apiboard\OpenAPI\Endpoint;
use Psr\Log\LogLevel;

class DeprecatedEndpoint implements Check
{
    use AcceptsRequest;

    protected Endpoint $endpoint;

    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function run(): array
    {
        if ($this->endpoint->deprecated() === false) {
            return [];
        }

        return [
            new Result(
                LogLevel::WARNING,
                "Deprecated endpoint {$this->endpoint->method()} {$this->endpoint->url()} used.",
                [
                    'pointer' => $this->endpoint->operation()->pointer()?->value(),
                ],
            ),
        ];
    }
}
