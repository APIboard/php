<?php

namespace Tests\Builders;

use Apiboard\Api;
use Closure;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ApiBuilder extends Builder
{
    protected ?string $id = null;

    protected ?string $openapi = null;

    protected ?Closure $checkRunner = null;

    protected ?LoggerInterface $logger = null;

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function openapi(string $openapi): self
    {
        $this->openapi = $openapi;

        return $this;
    }

    public function checkRunner(Closure $runner): self
    {
        $this->checkRunner = $runner;

        return $this;
    }

    public function logger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function make(): Api
    {
        $id = $this->id ?? bin2hex(random_bytes(4));

        return new Api(
            $id,
            $this->openapi ?? __DIR__.'/../__fixtures__/specification-example.json',
            $this->logger ?? new NullLogger(),
            $this->checkRunner ?? fn () => null,
        );
    }
}
