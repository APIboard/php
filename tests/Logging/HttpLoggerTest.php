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

test('it can log warning messages', function () {
    $called = false;
    $client = fakeClient(function () use (&$called) {
        $called = true;
    });
    $logger = HttpLoggerBuilder::new()->make($client);

    $logger->warning('Help!');

    expect($called)->toBeTrue();
});

test('it sends the warning message correctly through http', function () {
    /** @var ?RequestInterface $request */
    $request = null;
    $client = fakeClient(function (RequestInterface $sentRequest) use (&$request) {
        $request = $sentRequest;
    });
    $logger = HttpLoggerBuilder::new()->make($client);

    $logger->warning('::message::', [
        'context' => 'key!',
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
});

test('it throws an exception for unsupported log levels', function (string $level) {
    $message = '';
    $called = false;
    $client = fakeClient(fn () => $called = true);
    $logger = HttpLoggerBuilder::new()->make($client);

    try {
        $logger->{$level}($level);
    } catch (BadMethodCallException $e) {
        $message = $e->getMessage();
    }

    expect($message)->toBe("Log level[$level] not implemented");
    expect($called)->toBeFalse();
})->with([
    'emergency',
    'alert',
    'critical',
    'error',
    'notice',
    'info',
    'debug',
]);
