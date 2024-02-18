<?php

use Apiboard\Api;
use Apiboard\Apiboard;
use Psr\Log\NullLogger;

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
            'apiboard_id' => 'api-example-id',
            'openapi' => '',
        ],
    ]);

    $apiboard->disable();
    $result = $apiboard->api('example');

    expect($result->logger())->toBeInstanceOf(NullLogger::class);
});
