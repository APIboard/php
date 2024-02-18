<?php

namespace Apiboard\Checks;

use Apiboard\Checks\Concerns\AcceptsRequest;
use Apiboard\OpenAPI\Endpoint;
use Psr\Log\LogLevel;

class DeprecatedOperation implements Check
{
    use AcceptsRequest;

    protected Endpoint $endpoint;

    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function run(): array
    {
        $results = [];

        if ($this->endpoint->deprecated() === false) {
            return $results;
        }

        if ($this->endpoint->matches($this->request)) {
            $results[] = new Result(
                LogLevel::WARNING,
                'Deprecated operation used.',
                [
                    'pointer' => $this->endpoint->operation()->pointer()?->value(),
                ],
            );
        }

        return $results;
    }
}
