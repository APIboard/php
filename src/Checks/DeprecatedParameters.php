<?php

namespace Apiboard\Checks;

use Apiboard\Checks\Concerns\AcceptsRequest;
use Apiboard\OpenAPI\Structure\Parameters;
use Psr\Log\LogLevel;

class DeprecatedParameters implements Check
{
    use AcceptsRequest;

    protected Parameters $parameters;

    public function __construct(Parameters $parameters)
    {
        $this->parameters = $parameters;
    }

    public function run(): array
    {
        $results = [];

        foreach ($this->parameters as $parameter) {
            if ($parameter->deprecated() === false) {
                continue;
            }

            $isUsed = match ($parameter->in()) {
                'header' => $this->request->hasHeader($parameter->name()),
                'query' => str_contains($this->request->getUri()->getQuery(), "{$parameter->name()}="),
                'path' => true,
                default => false,
            };

            if ($isUsed) {
                $results[] = new Result(
                    LogLevel::WARNING,
                    "Deprecated {$parameter->in()} parameter [{$parameter->name()}] used.",
                    [
                        'pointer' => $parameter->pointer()?->value(),
                    ],
                );
            }
        }

        return $results;
    }
}
