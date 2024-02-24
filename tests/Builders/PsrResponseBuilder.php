<?php

namespace Tests\Builders;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use PsrDiscovery\Discover;
use PsrMock\Psr17\ResponseFactory;

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
        $factory = Discover::httpStreamFactory();

        $this->body = $factory->createStream($body);

        return $this;
    }

    public function make(): ResponseInterface
    {
        $response = (new ResponseFactory())
            ->createResponse($this->status);

        foreach ($this->headers as $name => $value) {
            $response = $response->withAddedHeader($name, $value);
        }

        if ($this->body) {
            $response = $response->withBody($this->body);
        }

        return $response;
    }
}
