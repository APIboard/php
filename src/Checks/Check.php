<?php

namespace Apiboard\Checks;

use Apiboard\Checks\Results\Context;

interface Check
{
    /**
     * Runs the check against the given context.
     */
    public function run(Context $context): Context;
}
