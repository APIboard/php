<?php

namespace Apiboard\Checks;

use Psr\Http\Message\MessageInterface;

interface Check
{
    /**
     * The unique identifier for the check as
     * additional context from its results.
     */
    public function id(): string;

    /**
     * Returns all the results after running
     * the check against the given message.
     *
     * @return array<array-key,Result>
     */
    public function run(MessageInterface $message): array;
}
