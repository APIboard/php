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

    public function state(): array
    {
        return [
            'name' => $this->name,
            'in' => $this->in,
        ];
    }

    public static function fromState(array $state): static
    {
        return new static($state['name'], $state['in']);
    }
}
