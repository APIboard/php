<?php

namespace Tests\Checks;

use Apiboard\Checks\DeprecatedEndpoint;
use Apiboard\Checks\Result;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;

function deprecatedEndpoint(...$args)
{
    return new DeprecatedEndpoint(...$args);
}

it('returns no results when the endpoint is not deprecated', function () {
    $endpoint = EndpointBuilder::new()
        ->deprecated(false)
        ->make();
    $message = PsrRequestBuilder::new()->make();

    $result = deprecatedEndpoint($endpoint)->run($message);

    expect($result)->toBeEmpty();
});

it('returns a result when the endpoint is deprecated', function () {
    $endpoint = EndpointBuilder::new()
        ->deprecated(true)
        ->make();
    $message = PsrRequestBuilder::new()->make();

    $result = deprecatedEndpoint($endpoint)->run($message);

    expect($result)->toHaveCount(1);
    expect($result[0])->toBeInstanceOf(Result::class);
});
