<?php

namespace Apiboard\Checks;

use Apiboard\Context;

interface Check
{
    /**
     * The unique identifier for this check.
     */
    public function id(): string;

    /**
     * Runs the check against the given context.
     */
    public function run(Context $context): Context;
}
