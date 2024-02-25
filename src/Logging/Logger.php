<?php

namespace Apiboard\Logging;

use Apiboard\Checks\Results\Context;

interface Logger
{
    public function process(Context $context): void;

    public function trim(): void;
}
