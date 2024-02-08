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

    public function disable(): self
    {
        $this->enabled = false;

        return $this;
    }

    public function enable(): self
    {
        $this->enabled = true;

        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->enabled === false;
    }

    public function isEnabled(): bool
    {
        return $this->enabled === true;
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
            $this->resolveSampledChecksRunner($api['sample_rate'] ?? 1),
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

    protected function resolveSampledChecksRunner(int|float $rate): Closure
    {
        $sampler = new Sampler($rate, $this->runChecksCallback);

        return function (Checks $checks) use ($sampler) {
            ($this->beforeRunningChecks)($checks);

            $sampler->__invoke($checks);
        };
    }
}
