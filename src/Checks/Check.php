<?php

namespace Apiboard\Checks;

use Apiboard\Context;

interface Check
{
    /**
     * Runs the check against the given context.
     */
    public function run(Context $context): Context;
}
