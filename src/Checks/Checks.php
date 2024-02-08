<?php

namespace Apiboard\Checks;

use Apiboard\Api;
use Psr\Http\Message\MessageInterface;

class Checks
{
    protected Api $api;

    protected MessageInterface $message;

    /**
     * @var array<array-key,Check>
     */
    protected array $checks = [];

    public function __construct(Api $api, MessageInterface $message)
    {
        $this->api = $api;
        $this->message = $message;
    }

    public function api(): Api
    {
        return $this->api;
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
            foreach ($check->run($this->message) as $result) {
                $this->api->logger()->log($result->severity(), $result->summary(), [
                    'api' => $this->api->id(),
                    'check' => $check->id(),
                    'details' => $result->details(),
                ]);
            }
        }
    }
}
