<?php

namespace Tests\Checks;

use Apiboard\OpenAPI\Endpoint;
use Psr\Http\Message\MessageInterface;
use Tests\Builders\ChecksBuilder;
use Tests\Builders\PsrRequestBuilder;
use Tests\Builders\PsrResponseBuilder;
use Tests\Builders\ResultBuilder;

test('it can return the endpoint', function () {
    $checks = ChecksBuilder::new()->make();

    $result = $checks->endpoint();

    expect($result)->toBeInstanceOf(Endpoint::class);
});

test('it can return the message', function () {
    $checks = ChecksBuilder::new()->make();

    $result = $checks->message();

    expect($result)->toBeInstanceOf(MessageInterface::class);
});

test('it logs the results for every check added when invoked', function () {
    $logger = arrayLogger();
    $check = testCheck();
    $check->addResult(ResultBuilder::new()->make());
    $checks = ChecksBuilder::new()
        ->logger($logger)
        ->make();

    $checks->add($check)->__invoke();

    $logger->assertNotEmpty();
});

test('it always uses the message defined on the instance on every check', function () {
    $request = PsrRequestBuilder::new()->make();
    $check = testCheck();
    $check->message(PsrResponseBuilder::new()->make());
    $checks = ChecksBuilder::new()
        ->message($request)
        ->make();

    $checks->add($check)->__invoke();

    $check->assertUsingMessage($request);
});
