<?php

use Apiboard\OpenAPI\Endpoint;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

function arrayLogger(): ArrayLogger
{
    return new ArrayLogger();
}

class ArrayLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var array<string, array>
     */
    protected array $logged = [];

    public function assertWarning(Endpoint $endpoint, string $message, array $context = []): void
    {
        $this->assertLogged('warning', $endpoint, $message, $context);
    }

    public function assertEmpty(): void
    {
        expect($this->logged)->toBeEmpty();
    }

    public function assertLogged(string $level, Endpoint $endpoint, string $message, array $context): void
    {
        expect($this->logged)->toHaveKey($level);
        expect($this->logged[$level])->toContain([
            'message' => $message,
            'context' => array_merge([
                'api' => $endpoint->api()->id(),
                'operation' => [
                    'method' => $endpoint->method(),
                    'url' => $endpoint->url(),
                ],
            ], $context),
        ]);
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->logged[$level][] = [
            'message' => $message,
            'context' => $context,
        ];
    }
}
