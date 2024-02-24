<?php

namespace Apiboard;

use Apiboard\Checks\Checks;
use Apiboard\Logging\Sampler;
use Closure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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

    public function api(string $name): Api
    {
        $api = $this->apis[$name];

        $sampler = new Sampler(
            $api['sample_rate'] ?? 1,
            $this->resolveChecksRunner(),
        );

        return new Api(
            $api['openapi'],
            $this->resolveLogger($api['channel'] ?? null),
            fn (Checks $checks) => $sampler->__invoke($checks)
        );
    }

    public function inspect(string $name, RequestInterface $request, ?ResponseInterface $response = null): void
    {
        $config = $this->apis[$name];

        $api = new Api(
            $config['openapi'],
            $this->resolveLogger($config['channel'] ?? null),
            $this->resolveChecksRunner(),
        );

        $sampler = new Sampler(
            $config['sample_rate'] ?? 1,
            fn () => $api->inspect($request, $response),
        );

        $sampler->__invoke();
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

    protected function resolveChecksRunner(): Closure
    {
        if ($this->isDisabled()) {
            return fn () => null;
        }

        return function (Checks $checks) {
            ($this->beforeRunningChecks)($checks);

            ($this->runChecksCallback)($checks);
        };
    }
}
