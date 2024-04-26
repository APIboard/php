<?php

namespace Apiboard\Checks;

use Apiboard\Context;

interface Check
{
    public function run(Context $context): Context;
}
