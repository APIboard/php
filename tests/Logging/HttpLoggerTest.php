<?php

use Apiboard\Logging\HttpLogger;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Tests\Builders\HttpLoggerBuilder;
use Tests\Builders\PsrResponseBuilder;

function fakeClient(?Closure $callback = null): ClientInterface
{
    return new class($callback) implements ClientInterface
    {
        protected ?Closure $onSendCallback;

        public function __construct(?Closure $onSendCallback)
        {
            $this->onSendCallback = $onSendCallback;
        }

        public function sendRequest(RequestInterface $request): ResponseInterface
        {
            if ($callback = $this->onSendCallback) {
                $response = $callback($request);

                if ($response instanceof ResponseInterface) {
                    return $response;
                }
            }

            return PsrResponseBuilder::new()->make();
        }
    };
}

test('it implements a logger interface', function () {
    expect(HttpLogger::class)->toImplement(LoggerInterface::class);
});

test('it can log messages with level', function (string $level) {
    $called = false;
    $client = fakeClient(function () use (&$called) {
        $called = true;
    });
    $logger = HttpLoggerBuilder::new()->make($client);

    $logger->{$level}('Help!');

    expect($called)->toBeTrue();
})->with([
    'emergency',
    'alert',
    'critical',
    'error',
    'warning',
    'notice',
    'info',
    'debug',
]);

test('it sends the message correctly through http', function () {
    /** @var ?RequestInterface $request */
    $request = null;
    $client = fakeClient(function (RequestInterface $sentRequest) use (&$request) {
        $request = $sentRequest;
    });
    $logger = HttpLoggerBuilder::new()->make($client);

    $logger->log('<level>', '<message>', [
        '<key>' => '<value>',
    ]);

    expect($request)->toBeInstanceOf(RequestInterface::class);
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->__toString())->toBe('https://apiboard.dev/api/logs');
    expect($request->getHeaders())->toBe([
        'Host' => ['apiboard.dev'],
        'Authorization' => ['Bearer ::token::'],
        'Accept' => ['application/json'],
        'Content-Type' => ['application/json'],
    ]);
    expect($request->getBody()->getContents())->toEqual(json_encode([
        'logged_at' => (new DateTime())->format('Y-m-d\TH:i:sP'),
        'level' => '<level>',
        'message' => '<message>',
        'context' => [
            '<key>' => '<value>',
        ],
    ]));
});
