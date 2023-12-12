<?php

use Apiboard\OpenAPI\Endpoint;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

function arrayLogger(): ArrayLogger
{
    return new ArrayLogger();
}

class ArrayLogger implements LoggerInterface
{
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
                    'uri' => $endpoint->uri(),
                ],
            ], $context),
        ]);
    }

    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->logged[$level][] = [
            'message' => $message,
            'context' => $context,
        ];
    }
}
