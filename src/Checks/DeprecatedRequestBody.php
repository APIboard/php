<?php

namespace Apiboard\Checks;

use Apiboard\OpenAPI\Structure\RequestBody;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LogLevel;

class DeprecatedRequestBody implements Check
{
    protected RequestBody $requestBody;

    public function __construct(RequestBody $requestBody)
    {
        $this->requestBody = $requestBody;
    }

    public function id(): string
    {
        return 'deprecated-request-body';
    }

    public function run(MessageInterface $message): array
    {
        $results = [];

        if ($message instanceof RequestInterface) {
            $mediaType = $this->requestBody->content()[$message->getHeaderLine('Content-Type')] ?? null;

            if ($mediaType?->schema()->deprecated()) {
                $results[] = new Result(
                    LogLevel::WARNING,
                    'Deprecated request body schema used.',
                    [
                        'pointer' => $mediaType->pointer()?->value(),
                    ],
                );
            }
        }

        return $results;
    }
}
