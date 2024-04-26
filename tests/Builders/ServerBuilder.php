<?php

namespace Tests\Builders;

use Apiboard\OpenAPI\Structure\Server;

class ServerBuilder extends Builder
{
    protected array $data = [
        'url' => 'https://api.example.com',
    ];

    public function url(string $url): self
    {
        $this->data['url'] = $url;

        return $this;
    }

    public function make(): Server
    {
        return new Server($this->data);
    }
}
