<?php

namespace Apiboard\Checks;

use Apiboard\Checks\Concerns\AcceptsRequest;
use Apiboard\OpenAPI\Structure\RequestBody;
use Psr\Log\LogLevel;

class DeprecatedRequestBody implements Check
{
    use AcceptsRequest;

    protected RequestBody $requestBody;

    public function __construct(RequestBody $requestBody)
    {
        $this->requestBody = $requestBody;
    }

    public function run(): array
    {
        $results = [];

        $mediaType = $this->requestBody->content()[$this->request->getHeaderLine('Content-Type')] ?? null;

        if ($mediaType?->schema()->deprecated()) {
            $results[] = new Result(
                LogLevel::WARNING,
                'Deprecated request body schema used.',
                [
                    'pointer' => $mediaType->pointer()?->value(),
                ],
            );
        }

        return $results;
    }
}
