<?php

namespace Tests\Builders;

abstract class Builder
{
    public static function new(): static
    {
        return new static;
    }
}
