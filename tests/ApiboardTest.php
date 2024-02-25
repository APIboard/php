<?php

use Apiboard\Apiboard;
use Apiboard\Checks\Checks;
use Tests\Builders\PsrRequestBuilder;
use Tests\Builders\PsrResponseBuilder;

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

test('it can inspect traffic for an api by name when enabled', function () {
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

    expect($inspected)->toHaveCount(1);
});

test('it does not inspect traffic for an api by name when disabled', function () {
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
    $apiboard->disable();

    $apiboard->inspect('example', $request, $response);

    expect($inspected)->toHaveCount(0);
});
