<?php

namespace Tests;

use Apiboard\Checks\Checks;
use Apiboard\OpenAPI\Structure\Document;
use Psr\Log\LoggerInterface;
use Tests\Builders\ApiBuilder;
use Tests\Builders\PsrRequestBuilder;
use Tests\Builders\PsrResponseBuilder;

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

it('runs the checks when inspecting traffic', function () {
    $runner = function (...$args) {
        expect($args)->toHaveCount(1);
        expect($args[0])->toBeInstanceOf(Checks::class);
    };
    $api = ApiBuilder::new()
        ->checkRunner($runner)
        ->make();
    $request = PsrRequestBuilder::new()->make();
    $response = PsrResponseBuilder::new()->make();

    $api->inspect($request, $response);
});

it('runs the checks when inspecting only a request', function () {
    $runner = function (...$args) {
        expect($args)->toHaveCount(1);
        expect($args[0])->toBeInstanceOf(Checks::class);
    };
    $api = ApiBuilder::new()
        ->checkRunner($runner)
        ->make();
    $request = PsrRequestBuilder::new()->make();

    $api->inspect($request);
});
