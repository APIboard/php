<?php

namespace Tests\Builders;

use Apiboard\Checks\Check;
use Apiboard\Checks\Results\Result;

class ResultBuilder extends Builder
{
    protected ?Check $check;

    public function check(Check $check): self
    {
        $this->check = $check;

        return $this;
    }

    public function make(): Result
    {
        return Result::new(
            $this->check ?? testCheck(),
        );
    }
}
