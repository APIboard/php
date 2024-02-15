<?php

namespace Tests\Checks;

use Apiboard\OpenAPI\Endpoint;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tests\Builders\ChecksBuilder;
use Tests\Builders\ResultBuilder;

test('it can return the endpoint', function () {
    $checks = ChecksBuilder::new()->make();

    $result = $checks->endpoint();

    expect($result)->toBeInstanceOf(Endpoint::class);
});

test('it can return the request', function () {
    $checks = ChecksBuilder::new()->make();

    $result = $checks->request();

    expect($result)->toBeInstanceOf(RequestInterface::class);
});

test('it can return the response', function () {
    $checks = ChecksBuilder::new()->make();

    $result = $checks->response();

    expect($result)->toBeInstanceOf(ResponseInterface::class);
});

test('it logs the results for every check added when invoked', function () {
    $logger = arrayLogger();
    $check = arrayCheck();
    $check->addResult(
        ResultBuilder::new()->make(),
    );
    $checks = ChecksBuilder::new()
        ->logger($logger)
        ->make();

    $checks->add($check)->__invoke();

    $check->assertRanFor($checks->request());
    $check->assertRanFor($checks->response());
    $logger->assertNotEmpty();
});
