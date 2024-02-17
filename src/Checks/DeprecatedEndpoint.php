<?php

namespace Apiboard\Checks;

use Apiboard\OpenAPI\Endpoint;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LogLevel;

class DeprecatedEndpoint implements Check
{
    protected Endpoint $endpoint;

    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function id(): string
    {
        return 'deprecated-endpoint';
    }

    public function run(MessageInterface $message): array
    {
        $results = [];

        if ($this->endpoint->deprecated() === false) {
            return $results;
        }

        if ($message instanceof RequestInterface) {
            $results[] = new Result(
                LogLevel::WARNING,
                "Deprecated endpoint {$this->endpoint->method()} {$this->endpoint->url()} used.",
                [
                    'pointer' => $this->endpoint->operation()->pointer()?->value(),
                ],
            );
        }

        return $results;
    }
}
