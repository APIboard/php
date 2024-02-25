<?php

namespace Tests\Checks;

use Apiboard\Api;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tests\Builders\ChecksBuilder;
use Tests\Builders\PsrResponseBuilder;
use Tests\Builders\ResultBuilder;

test('it can return the api', function () {
    $checks = ChecksBuilder::new()->make();

    $result = $checks->api();

    expect($result)->toBeInstanceOf(Api::class);
});

test('it can return the request', function () {
    $checks = ChecksBuilder::new()->make();

    $result = $checks->request();

    expect($result)->toBeInstanceOf(RequestInterface::class);
});

test('it can return the response', function () {
    $checks = ChecksBuilder::new()
        ->response(PsrResponseBuilder::new()->make())
        ->make();

    $result = $checks->response();

    expect($result)->toBeInstanceOf(ResponseInterface::class);
});

test('it logs the results for every check added when invoked', function () {
    $logger = arrayLogger();
    $check = testCheck();
    $check->addResult(
        ResultBuilder::new()->check($check)->make()
    );
    $checks = ChecksBuilder::new()
        ->logger($logger)
        ->make();

    $checks->add($check)->__invoke();

    $logger->assertNotEmpty();
});
