<?php

namespace Tests\Builders;

use Apiboard\Checks\Result;
use Psr\Log\LogLevel;

class ResultBuilder extends Builder
{
    public function make(): Result
    {
        return new Result(
            LogLevel::WARNING,
            '',
        );
    }
}
