<?php

namespace Apiboard\Reporting\Reports;

use Apiboard\Checks\Results\Result;

interface Report
{
    public static function fromState(array $state): static;

    public function id(): string;

    public function state(): array;

    public function include(Result ...$results): void;
}
