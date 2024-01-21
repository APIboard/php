<?php

use Apiboard\Api;
use Apiboard\Apiboard;

test('it can get the correct api from the config by name', function () {
    $apiboard = new Apiboard([
        'example' => [
            'apiboard_id' => 'api-example-id',
            'openapi' => '',
        ],
        'other-example' => [
            'apiboard_id' => 'other-api-example-id',
            'openapi' => '',
        ],
    ]);

    $result = $apiboard->api('example');

    expect($result)->toBeInstanceOf(Api::class);
    expect($result->id())->toBe('api-example-id');
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

test('it does not return an api when it is disabled', function () {
    $apiboard = new Apiboard([
        'example' => [
            'apiboard_id' => 'api-example-id',
            'openapi' => '',
        ],
    ]);

    $apiboard->disable();
    $result = $apiboard->api('example');

    expect($result)->toBeNull();
});
