<?php

namespace Apiboard\Logging;

use Apiboard\Context;
use Apiboard\Reporting\Reporter;
use DateTime;

interface Logger
{
    public function recap(Reporter $reporter): int;

    public function log(Context $context): void;

    public function trim(DateTime $before): void;
}
