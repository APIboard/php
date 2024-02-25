<?php

namespace Apiboard\Checks;

use Apiboard\Checks\Concerns\AcceptsRequest;
use Apiboard\Checks\Results\Context;
use Apiboard\Checks\Results\Result;

class DeprecatedParameters implements Check
{
    use AcceptsRequest;

    public function run(Context $context): Context
    {
        $parameters = $context->endpoint()?->parameters();

        if ($parameters === null) {
            return $context;
        }

        foreach ($parameters as $parameter) {
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
                $context->add(
                    Result::new($this, $parameter),
                );
            }
        }

        return $context;
    }
}
