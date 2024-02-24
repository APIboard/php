<?php

use Apiboard\Api;
use Apiboard\Apiboard;
use Apiboard\Checks\Checks;
use Psr\Log\NullLogger;
use Tests\Builders\PsrRequestBuilder;
use Tests\Builders\PsrResponseBuilder;

test('it can get the correct api from the config by name', function () {
    $apiboard = new Apiboard([
        'example' => [
            'openapi' => '{{example-openapi}}',
        ],
        'other-example' => [
            'openapi' => '{{other-example-openapi}}',
        ],
    ]);

    $result = $apiboard->api('example');

    expect($result)->toBeInstanceOf(Api::class);
    expect($result->openapi())->toEqual('{{example-openapi}}');
});

test('it can be disabled and enabled', function () {
    $apiboard = new Apiboard([]);

    expect($apiboard->isEnabled())->toBeTrue();
    expect($apiboard->isDisabled())->toBeFalse();

    $apiboard->disable();

    expect($apiboard->isEnabled())->toBeFalse();
    expect($apiboard->isDisabled())->toBeTrue();

    $apiboard->enable();

    expect($apiboard->isEnabled())->toBeTrue();
    expect($apiboard->isDisabled())->toBeFalse();
});

test('it uses a null logger when disabled', function () {
    $apiboard = new Apiboard([
        'example' => [
            'openapi' => '',
        ],
    ]);

    $apiboard->disable();
    $result = $apiboard->api('example');

    expect($result->logger())->toBeInstanceOf(NullLogger::class);
});

test('it can inspect traffic for an api by name', function () {
    $request = PsrRequestBuilder::new()->make();
    $response = PsrResponseBuilder::new()->make();
    $apiboard = new Apiboard([
        'example' => [
            'openapi' => __DIR__.'/__fixtures__/specification-example.json',
        ],
    ]);
    $inspected = [];
    $apiboard->runChecksUsing(function (Checks $checks) use (&$inspected) {
        $inspected[] = $checks;
    });

    $apiboard->inspect('example', $request, $response);

    expect($inspected)->toHaveCount(2);
});
