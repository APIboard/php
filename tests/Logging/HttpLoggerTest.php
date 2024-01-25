<?php

use Apiboard\Logging\HttpLogger;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use PsrMock\Psr18\Client;
use Tests\Builders\HttpLoggerBuilder;
use Tests\Builders\PsrResponseBuilder;

test('it implements a logger interface', function () {
    expect(HttpLogger::class)->toImplement(LoggerInterface::class);
});

test('it can log messages with level', function (string $level) {
    $client = new Client();
    $client->addResponseWildcard(PsrResponseBuilder::new()->make());
    $logger = HttpLoggerBuilder::new()->make($client);

    $logger->{$level}('Help!');

    expect($client->getTimeline())->not->toBeEmpty();
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
    $client = new Client();
    $client->addResponseWildcard(PsrResponseBuilder::new()->make());
    $logger = HttpLoggerBuilder::new()->make($client);

    $logger->log('<level>', '<message>', [
        '<key>' => '<value>',
    ]);

    $request = $client->getTimeline()[0]['request'];
    expect($request)->toBeInstanceOf(RequestInterface::class);
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->__toString())->toBe('https://apiboard.dev/api/logs');
    expect($request->getHeaders())->toBe([
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
