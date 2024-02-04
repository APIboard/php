<?php

namespace Tests\Builders;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use PsrDiscovery\Discover;

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
        $factory = Discover::httpStreamFactory();

        $this->body = $factory->createStream($body);

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

    public function json(?array $body = null): self
    {
        $this->header('Content-Type', 'application/json');

        if ($body) {
            $this->body(json_encode($body));
        }

        return $this;
    }

    public function make(): RequestInterface
    {
        $request = Discover::httpRequestFactory()
            ->createRequest($this->method, $this->uri.$this->query);

        foreach ($this->headers as $name => $value) {
            $request = $request->withAddedHeader($name, $value);
        }

        if ($this->body) {
            $request = $request->withBody($this->body);
        }

        return $request;
    }
}
