<?php

namespace Tests\Checks;

use Apiboard\Checks\DeprecatedRequestBody;
use Apiboard\Checks\Result;
use Tests\Builders\PsrRequestBuilder;
use Tests\Builders\PsrResponseBuilder;
use Tests\Builders\RequestBodyBuilder;
use Tests\Builders\SchemaBuilder;

function deprecatedRequestBody(...$args)
{
    return new DeprecatedRequestBody(...$args);
}

it('returns no results when no deprecated request body schema is used', function () {
    $message = PsrRequestBuilder::new()
        ->json([
            'foo' => [
                'bar' => '<value>',
            ],
        ])
        ->make();
    $requestBody = RequestBodyBuilder::new()
        ->json(
            SchemaBuilder::new()->make()
        )
        ->make();

    $result = deprecatedRequestBody($requestBody)->run($message);

    expect($result)->toBeEmpty();
});

it('returns no results when the message is a response', function () {
    $message = PsrResponseBuilder::new()->make();
    $requestBody = RequestBodyBuilder::new()->make();

    $result = deprecatedRequestBody($requestBody)->run($message);

    expect($result)->toBeEmpty();
});

it('returns results when a deprecated request body schema is used', function () {
    $message = PsrRequestBuilder::new()
        ->json([
            'foo' => [
                'bar' => '<value>',
            ],
        ])
        ->make();
    $requestBody = RequestBodyBuilder::new()
        ->json(
            SchemaBuilder::new()->deprecated()->make()
        )
        ->make();

    $result = deprecatedRequestBody($requestBody)->run($message);

    expect($result)->toHaveCount(1);
    expect($result[0])->toBeInstanceOf(Result::class);
});
