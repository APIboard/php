<?php

namespace Apiboard;

use Apiboard\Checks\Checks;
use Apiboard\Logging\Sampler;
use Closure;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Apiboard
{
    protected array $apis;

    protected bool $enabled = true;

    protected Closure $beforeRunningChecks;

    protected Closure $runChecksCallback;

    protected Closure $logResolverCallback;

    public function __construct(array $apis)
    {
        $this->apis = $apis;
        $this->beforeRunningChecks = fn () => null;
        $this->runChecksCallback = fn (Checks $checks) => $checks->__invoke();
        $this->logResolverCallback = fn () => new NullLogger();
    }

    public function beforeRunningChecks(Closure $beforeRunningChecks): void
    {
        $this->beforeRunningChecks = $beforeRunningChecks;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function isDisabled(): bool
    {
        return $this->enabled === false;
    }

    public function isEnabled(): bool
    {
        return $this->enabled === true;
    }

    public function runChecksUsing(Closure $callback): void
    {
        $this->runChecksCallback = $callback;
    }

    public function resolveLoggerUsing(Closure $callback): void
    {
        $this->logResolverCallback = $callback;
    }

    public function api(string $id): Api
    {
        $api = $this->apis[$id];

        return new Api(
            $api['apiboard_id'],
            $api['openapi'],
            $this->resolveLogger($api['channel'] ?? null),
            $this->resolveChecksRunner($api['sample_rate'] ?? 1),
        );
    }

    protected function resolveLogger(string|LoggerInterface|null $logger): LoggerInterface
    {
        $logResolver = $this->logResolverCallback;

        return match (true) {
            $this->isDisabled() => new NullLogger(),
            $logger instanceof LoggerInterface => $logger,
            default => $logResolver($logger),
        };
    }

    protected function resolveChecksRunner(int|float $rate): Closure
    {
        if ($this->isDisabled()) {
            return fn () => null;
        }

        $sampler = new Sampler($rate, $this->runChecksCallback);

        return function (Checks $checks) use ($sampler) {
            ($this->beforeRunningChecks)($checks);

            $sampler->__invoke($checks);
        };
    }
}
