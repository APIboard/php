<?php

namespace Tests;

use Apiboard\Logging\Logger;
use Tests\Builders\ApiBuilder;

it('can return the api identifier', function () {
    $api = ApiBuilder::new()
        ->id('<id>')
        ->make();

    $result = $api->id();

    expect($result)->toBe('<id>');
});

it('can return the openapi path', function () {
    $api = ApiBuilder::new()
        ->openapi('<path-to-openapi-spec>')
        ->make();

    $result = $api->openapi();

    expect($result)->toBe('<path-to-openapi-spec>');
});

it('can return a logger', function () {
    $api = ApiBuilder::new()->make();

    $result = $api->log();

    expect($result)->toBeInstanceOf(Logger::class);
});
