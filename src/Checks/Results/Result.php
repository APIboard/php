<?php

namespace Apiboard\Checks\Results;

interface Result
{
    public static function fromState(array $state): static;

    public function state(): array;
}
