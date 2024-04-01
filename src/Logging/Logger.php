<?php

namespace Apiboard\Logging;

use Apiboard\Context;
use Apiboard\Reporting\Reporter;
use DateTime;

interface Logger
{
    public function recap(Reporter $reporter): int;

    public function process(Context $context): void;

    public function trim(DateTime $before): void;
}
