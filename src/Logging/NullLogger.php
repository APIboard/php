<?php

namespace Apiboard\Logging;

use Apiboard\Context;
use Apiboard\Reporting\Reporter;
use DateTime;

class NullLogger implements Logger
{
    public function recap(Reporter $reporter): int
    {
        return 0;
    }

    public function process(Context $context): void
    {
    }

    public function trim(DateTime $before): void
    {
    }
}
