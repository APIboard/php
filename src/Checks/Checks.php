<?php

namespace Apiboard\Checks;

use Apiboard\OpenAPI\Endpoint;
use Psr\Http\Message\MessageInterface;
use Psr\Log\LoggerInterface;

class Checks
{
    protected Endpoint $endpoint;

    protected LoggerInterface $logger;

    protected MessageInterface $message;

    /**
     * @var array<array-key,Check>
     */
    protected array $checks = [];

    public function __construct(Endpoint $endpoint, LoggerInterface $logger, MessageInterface $message)
    {
        $this->endpoint = $endpoint;
        $this->logger = $logger;
        $this->message = $message;
    }

    public function endpoint(): Endpoint
    {
        return $this->endpoint;
    }

    public function message(): MessageInterface
    {
        return $this->message;
    }

    public function add(Check ...$checks): self
    {
        foreach ($checks as $check) {
            $this->checks[] = $check;
        }

        return $this;
    }

    public function __invoke(): void
    {
        foreach ($this->checks as $check) {
            $check->message($this->message);

            $results = $check->run();

            foreach ($results as $result) {
                $this->logger->log($result->severity(), $result->summary(), [
                    'method' => $this->endpoint->method(),
                    'url' => $this->endpoint()->url(),
                    'check' => get_class($check),
                    'details' => $result->details(),
                ]);
            }
        }
    }
}
