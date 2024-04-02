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

    public function state(): array
    {
        return [
            'url' => $this->url,
        ];
    }

    public static function fromState(array $state): static
    {
        return new static($state['url']);
    }
}
