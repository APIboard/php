<?php

namespace Apiboard\Logging;

use Apiboard\Checks\Results\Context;

class NullLogger implements Logger
{
    public function process(Context $context): void
    {
    }
}
