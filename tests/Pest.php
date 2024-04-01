<?php

use Apiboard\Checks\Check;
use Apiboard\Checks\Results\Context;
use Apiboard\Checks\Results\Result;
use Apiboard\Logging\Logger;
use Apiboard\Reporting\Reporter;

function arrayLogger(): ArrayLogger
{
    return new ArrayLogger();
}

function testCheck(): TestCheck
{
    return new TestCheck();
}

class ArrayLogger implements Logger
{
    /**
     * @var array<string, array>
     */
    protected array $logged = [];

    public function assertNotEmpty(): void
    {
        expect($this->logged)->not->toBeEmpty();
    }

    public function recap(Reporter $reporter): int
    {
        return count($this->logged);
    }

    public function process(Context $context): void
    {
        $this->logged[] = $context;
    }

    public function trim(DateTime $before): void
    {
    }
}

class TestCheck implements Check
{
    public function id(): string
    {
        return 'test-check';
    }

    public function run(Context $context): Context
    {
        $result = new class implements Result
        {
            public function check(): Check
            {
                return new TestCheck();
            }

            public function data(): array
            {
                return [];
            }

            public function loggedAt(): DateTime
            {
                return new DateTime();
            }
        };

        $context->add($result);

        return $context;
    }
}
