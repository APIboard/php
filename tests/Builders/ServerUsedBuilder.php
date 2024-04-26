<?php

namespace Tests\Builders;

use Apiboard\Checks\Results\ServerUsed;

class ServerUsedBuilder extends Builder
{
    protected ?string $url = null;

    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function make(): ServerUsed
    {
        return new ServerUsed($this->url ?? 'https://api.example.com');
    }
}
