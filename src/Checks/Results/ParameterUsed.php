<?php

namespace Apiboard\Checks\Results;

class ParameterUsed implements Result
{
    protected string $name;

    protected string $in;

    public function __construct(string $name, string $in)
    {
        $this->name = $name;
        $this->in = $in;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function in(): string
    {
        return $this->in;
    }
}
