<?php

namespace Apiboard\Checks;

use Psr\Http\Message\MessageInterface;

interface Check
{
    /**
     * The message the check should be using.
     */
    public function message(MessageInterface $message): void;

    /**
     * Returns all the results after running.
     *
     * @return array<array-key,Result>
     */
    public function run(): array;
}
