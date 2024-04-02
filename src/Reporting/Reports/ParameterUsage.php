<?php

namespace Apiboard\Reporting\Reports;

use Apiboard\Checks\Results\ParameterUsed;
use Apiboard\Checks\Results\Result;
use Apiboard\Context;

class ParameterUsage implements Report
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
            $parameterResults = $this->parametersUsed(...$context->results());

            foreach ($parameterResults as $result) {
                $parameterContext = "{$context->api()->id()}:{$result->in()}:{$result->name()}";

                $state = $this->state[$parameterContext] ??= [
                    'api' => $context->api()->id(),
                    'name' => $result->name(),
                    'in' => $result->in(),
                    'operations' => [],
                ];

                $state['operations'] = array_unique([
                    ...$state['operations'],
                    [
                        'method' => $context->endpoint()->operation()->method(),
                        'uri' => $context->endpoint()->path()->uri(),
                    ],
                ]);

                $this->state[$parameterContext] = $state;
            }
        }
    }

    /**
     * @return array<array-key,ParameterUsed>
     */
    protected function parametersUsed(Result ...$results): array
    {
        return array_filter($results, function (Result $result) {
            return $result instanceof ParameterUsed;
        });
    }
}
