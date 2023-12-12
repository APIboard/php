<?php

namespace Tests\Builders;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class PsrResponseBuilder extends Builder
{
    protected int $status = 200;

    protected array $headers = [];

    protected ?StreamInterface $body = null;

    public function header(string $name, mixed $value): self
    {
        $this->headers[$name] = [$value];

        return $this;
    }

    public function status(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function body(string $body): self
    {
        $this->body = new Stream(
            fopen('data://text/plain;base64,'.base64_encode($body), 'r'),
        );

        return $this;
    }

    public function make(): ResponseInterface
    {
        return new Response($this->status, $this->headers, $this->body);
    }
}
