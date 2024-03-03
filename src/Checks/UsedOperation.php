<?php

namespace Apiboard\Checks;

use Apiboard\Checks\Concerns\AcceptsRequest;
use Apiboard\Checks\Results\Context;
use Apiboard\Checks\Results\Result;

class UsedOperation implements Check
{
    use AcceptsRequest;

    public function id(): string
    {
        return 'used-operation';
    }

    public function run(Context $context): Context
    {
        $endpoint = $context->endpoint();

        if ($endpoint === null) {
            return $context;
        }

        if ($endpoint->matches($this->request)) {
            $context->add(
                Result::new($this, [
                    'method' => $endpoint->method(),
                    'path' => $endpoint->path(),
                    'deprecated' => $endpoint->deprecated(),
                ]),
            );
        }

        return $context;
    }
}
