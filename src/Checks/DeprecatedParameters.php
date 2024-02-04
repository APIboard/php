<?php

namespace Apiboard\Checks;

use Apiboard\OpenAPI\Structure\Parameters;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LogLevel;

class DeprecatedParameters implements Check
{
    protected Parameters $parameters;

    public function __construct(Parameters $parameters)
    {
        $this->parameters = $parameters;
    }

    public function id(): string
    {
        return 'deprecated-parameters';
    }

    public function run(MessageInterface $message): array
    {
        $results = [];

        if ($message instanceof RequestInterface) {
            foreach ($this->parameters as $parameter) {
                if ($parameter->deprecated() === false) {
                    continue;
                }

                $isUsed = match ($parameter->in()) {
                    'header' => $message->hasHeader($parameter->name()),
                    'query' => str_contains($message->getUri()->getQuery(), "{$parameter->name()}="),
                    'path' => true,
                    default => false,
                };

                if ($isUsed) {
                    $results[] = new Result(
                        LogLevel::WARNING,
                        "Deprecated {$parameter->in()} parameter [{$parameter->name()}] used.",
                    );
                }
            }
        }

        return $results;
    }
}
