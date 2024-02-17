<?php

use Apiboard\Checks\Check;
use Apiboard\Checks\Result;
use Psr\Http\Message\MessageInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

function arrayLogger(): ArrayLogger
{
    return new ArrayLogger();
}

function testCheck(): TestCheck
{
    return new TestCheck();
}

class ArrayLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var array<string, array>
     */
    protected array $logged = [];

    public function assertNotEmpty(): void
    {
        expect($this->logged)->not->toBeEmpty();
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->logged[$level][] = [
            'message' => $message,
            'context' => $context,
        ];
    }
}

class TestCheck implements Check
{
    protected ?MessageInterface $message = null;

    protected array $results = [];

    public function message(MessageInterface $message): void
    {
        $this->message = $message;
    }

    public function addResult(Result $result): self
    {
        $this->results[] = $result;

        return $this;
    }

    public function run(): array
    {
        return $this->results;
    }

    public function assertUsingMessage(MessageInterface $message): void
    {
        expect($this->message)->not->toBeNull();
        expect($this->message)->toBe($message);
    }
}
