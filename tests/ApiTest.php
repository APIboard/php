<?php

namespace Tests;

use Apiboard\Checks\Checks;
use Apiboard\OpenAPI\Structure\Document;
use Psr\Log\LoggerInterface;
use Tests\Builders\ApiBuilder;
use Tests\Builders\PsrRequestBuilder;

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

it('can return the specification', function () {
    $api = ApiBuilder::new()->make();

    $result = $api->specification();

    expect($result)->toBeInstanceOf(Document::class);
});

it('can return a logger', function () {
    $api = ApiBuilder::new()->make();

    $result = $api->logger();

    expect($result)->toBeInstanceOf(LoggerInterface::class);
});

it('runs the checks when inspecting a message', function () {
    $runner = function (...$args) {
        expect($args)->toHaveCount(1);
        expect($args[0])->toBeInstanceOf(Checks::class);
    };
    $api = ApiBuilder::new()
        ->checkRunner($runner)
        ->make();
    $message = PsrRequestBuilder::new()->make();

    $api->inspect($message);
});
