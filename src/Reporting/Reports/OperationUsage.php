<?php

namespace Apiboard\Reporting\Reports;

use Apiboard\Checks\Results\Result;
use Apiboard\Checks\Results\ServerUsed;
use Apiboard\Context;

class OperationUsage implements Report
{
    protected array $state;

    private function __construct(array $state)
    {
        $this->state = $state;
    }

    public static function fromState(array $state): static
    {
        return new static($state);
    }

    public function state(): array
    {
        return $this->state;
    }

    public function include(Context ...$contexts): void
    {
        foreach ($contexts as $context) {
            $state = $this->state[$context->hash()] ??= [
                'api' => $context->api()->id(),
                'method' => $context->endpoint()->operation()->method(),
                'uri' => $context->endpoint()->path()->uri(),
                'servers' => [],
            ];

            $serverResults = $this->serversUsed(...$context->results());

            foreach ($serverResults as $result) {
                if (in_array($result->url(), $state['servers'])) {
                    continue;
                }

                $state['servers'][] = $result->url();
            }

            $this->state[$context->hash()] = $state;
        }
    }

    /**
     * @return array<array-key,ServerUsed>
     */
    protected function serversUsed(Result ...$results): array
    {
        return array_filter($results, function (Result $result) {
            return $result instanceof ServerUsed;
        });
    }
}
