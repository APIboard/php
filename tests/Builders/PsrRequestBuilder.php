<?php

namespace Tests\Builders;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

class PsrRequestBuilder extends Builder
{
    protected string $method = 'GET';

    protected string $uri = '/';

    protected array $headers = [];

    protected string $query = '';

    protected ?StreamInterface $body = null;

    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function uri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function body(string $body): self
    {
        $this->body = new Stream(
            fopen('data://text/plain;base64,'.base64_encode($body), 'r'),
        );

        return $this;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name][] = $value;

        return $this;
    }

    public function query(string $name, mixed $value): self
    {
        if ($this->query === '') {
            $this->query = "?{$name}=$value";

            return $this;
        }

        $this->query = $this->query."&{$name}={$value}";

        return $this;
    }

    public function make(): RequestInterface
    {
        $fullUri = $this->uri.$this->query;

        return new Request($this->method, $fullUri, $this->headers, $this->body);
    }
}
