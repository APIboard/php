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

function arrayCheck(): ArrayCheck
{
    return new ArrayCheck();
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

class ArrayCheck implements Check
{
    protected array $messages = [];

    protected array $results;

    public function id(): string
    {
        return 'array-check';
    }

    public function addResult(Result $result): self
    {
        $this->results[] = $result;

        return $this;
    }

    public function run(MessageInterface $message): array
    {
        $this->messages[] = $message;

        return $this->results;
    }

    public function assertRanFor(MessageInterface $messageInterface): void
    {
        expect($this->messages)->toContain($messageInterface);
    }
}
