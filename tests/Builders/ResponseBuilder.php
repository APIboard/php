<?php

namespace Tests\Builders;

use Apiboard\Http\Client\Response;
use Apiboard\OpenAPI\Structure\Response as OpenAPIResponse;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

class ResponseBuilder extends Builder
{
    protected array $responses = [];

    protected string $contentType = 'application/json';

    protected ?StreamInterface $body = null;

    public function __construct()
    {
        $this->responses = [
            200 => new OpenAPIResponse('200', [
                'content' => [
                    'application/json' => [],
                ],
            ]),
        ];
    }

    public function body(string $body): self
    {
        $this->body = new Stream(
            fopen('data://text/plain;base64,'.base64_encode($body), 'r'),
        );

        return $this;
    }

    public function responses(OpenAPIResponse ...$responses): self
    {
        foreach ($responses as $response) {
            $this->responses[$response->statusCode()] = $response;
        }

        return $this;
    }

    public function make(): Response
    {
        $response = PsrResponseBuilder::new()
            ->status(array_rand($this->responses))
            ->body($this->body ?? '')
            ->header('Content-Type', $this->contentType)
            ->make();

        $endpoint = EndpointBuilder::new()
            ->responses(...$this->responses)
            ->make();

        return new Response(
            $response,
            $endpoint,
        );
    }
}
