<?php

namespace Apiboard\Checks\Results;

class ServerUsed implements Result
{
    protected string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function url(): string
    {
        return $this->url;
    }
}
