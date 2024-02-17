<?php

namespace Tests\Builders;

use Apiboard\Checks\Checks;
use Apiboard\OpenAPI\Endpoint;
use Psr\Http\Message\MessageInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ChecksBuilder extends Builder
{
    protected ?Endpoint $endpoint = null;

    protected ?LoggerInterface $logger = null;

    protected ?MessageInterface $message = null;

    public function logger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function message(MessageInterface $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function make(): Checks
    {
        return new Checks(
            $this->endpoint ?? EndpointBuilder::new()->make(),
            $this->logger ?? new NullLogger(),
            $this->message ?? PsrRequestBuilder::new()->make(),
        );
    }
}
