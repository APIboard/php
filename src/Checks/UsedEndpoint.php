<?php

namespace Apiboard\Checks;

use Apiboard\Checks\Concerns\AcceptsRequest;
use Apiboard\Checks\Results\Context;

class UsedEndpoint implements Check
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
            $context->addResult($this, $endpoint->jsonSerialize(), [
                'method' => $endpoint->operation()->method(),
                'uri' => $endpoint->path()->uri(),
            ]);
        }

        return $context;
    }
}
