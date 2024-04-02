<?php

namespace Apiboard\Reporting\Reports;

use Apiboard\Context;

interface Report
{
    public static function fromState(array $state): static;

    public function state(): array;

    public function include(Context ...$contexts): void;
}
