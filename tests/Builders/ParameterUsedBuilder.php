<?php

namespace Tests\Builders;

use Apiboard\Checks\Results\ParameterUsed;

class ParameterUsedBuilder extends Builder
{
    protected ?string $name = null;

    protected ?string $in = null;

    public function query(string $name): self
    {
        $this->name = $name;
        $this->in = 'query';

        return $this;
    }

    public function path(string $name): self
    {
        $this->name = $name;
        $this->in = 'path';

        return $this;
    }

    public function header(string $name): self
    {
        $this->name = $name;
        $this->in = 'header';

        return $this;
    }

    public function make(): ParameterUsed
    {
        return new ParameterUsed(
            $this->name ?? 'parameter',
            $this->in ?? 'query',
        );
    }
}
