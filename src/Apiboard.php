<?php

namespace Apiboard;

use Apiboard\Checks\Check;
use Closure;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Apiboard
{
    protected array $apis;

    protected bool $enabled = true;

    protected Closure $runChecksCallback;

    protected Closure $logResolverCallback;

    public function __construct(array $apis)
    {
        $this->apis = $apis;
        $this->runChecksCallback = fn (Check $check) => $check->__invoke();
        $this->logResolverCallback = fn () => new NullLogger();
    }

    public function disable(): self
    {
        $this->enabled = false;

        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->enabled === false;
    }

    public function runChecksUsing(Closure $callback): self
    {
        $this->runChecksCallback = $callback;

        return $this;
    }

    public function resolveLoggerUsing(Closure $callback): self
    {
        $this->logResolverCallback = $callback;

        return $this;
    }

    public function api(string $id): ?Api
    {
        if ($this->enabled === false) {
            return null;
        }

        $api = $this->apis[$id];

        return new Api(
            $api['apiboard_id'],
            $api['openapi'],
            $this->resolveLogger($api['channel'] ?? null),
            $this->runChecksCallback,
        );
    }

    protected function resolveLogger(string|LoggerInterface|null $logger): LoggerInterface
    {
        $logResolver = $this->logResolverCallback;

        return match (true) {
            $logger instanceof LoggerInterface => $logger,
            is_string($logger) => $logResolver($logger),
            default => $logResolver(null),
        };
    }
}
