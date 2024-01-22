<?php

namespace Apiboard\Logging;

use BadMethodCallException;
use DateTime;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Stringable;

class HttpLogger implements LoggerInterface
{
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

    public function emergency(string|Stringable $message, array $context = []): void
    {
        throw new BadMethodCallException('Log level[emergency] not implemented');
    }

    public function alert(string|Stringable $message, array $context = []): void
    {
        throw new BadMethodCallException('Log level[alert] not implemented');
    }

    public function critical(string|Stringable $message, array $context = []): void
    {
        throw new BadMethodCallException('Log level[critical] not implemented');
    }

    public function error(string|Stringable $message, array $context = []): void
    {
        throw new BadMethodCallException('Log level[error] not implemented');
    }

    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    public function notice(string|Stringable $message, array $context = []): void
    {
        throw new BadMethodCallException('Log level[notice] not implemented');
    }

    public function info(string|Stringable $message, array $context = []): void
    {
        throw new BadMethodCallException('Log level[info] not implemented');
    }

    public function debug(string|Stringable $message, array $context = []): void
    {
        throw new BadMethodCallException('Log level[debug] not implemented');
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
                'message' => $message,
                'context' => $context,
            ])));

        $this->http->sendRequest($request);
    }
}
