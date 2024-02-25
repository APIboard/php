<?php

namespace Tests;

use Apiboard\OpenAPI\Structure\Document;
use Tests\Builders\ApiBuilder;

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
