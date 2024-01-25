<?php

namespace Apiboard\Logging;

use DateTime;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

class HttpLogger implements LoggerInterface
{
    use LoggerTrait;

    private string $token;

    private ClientInterface $http;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    public function __construct(
        string $token,
        ClientInterface $http,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
    ) {
        $this->token = $token;
        $this->http = $http;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $request = $this->requestFactory
            ->createRequest('POST', 'https://apiboard.dev/api/logs')
            ->withHeader('Authorization', 'Bearer ' . $this->token)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->streamFactory->createStream(json_encode([
                'logged_at' => (new DateTime())->format('Y-m-d\TH:i:sP'),
                'level' => $level,
                'message' => $message,
                'context' => $context,
            ])));

        $this->http->sendRequest($request);
    }
}
