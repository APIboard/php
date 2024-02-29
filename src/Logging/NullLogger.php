<?php

namespace Apiboard\Logging;

use Apiboard\Checks\Results\Context;
use Apiboard\Reporting\Reporter;

class NullLogger implements Logger
{
    public function recap(Reporter $reporter): int
    {
        return 0;
    }

    public function process(Context $context): void
    {
    }

    public function trim(): void
    {
    }
}
