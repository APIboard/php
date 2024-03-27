<?php

namespace Apiboard\Reporting;

use Apiboard\Checks\Results\Context;

interface Report
{
    public static function fromState(array $state): static;

    public function id(): string;

    public function state(): array;

    public function include(Context ...$contexts): void;
}
