<?php

namespace Tests\OpenAPI;

use Apiboard\Checks\DeprecatedOperation;
use Apiboard\Checks\DeprecatedParameters;
use Apiboard\OpenAPI\Endpoint;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;
use Tests\Builders\PsrResponseBuilder;
use Tests\Builders\ResponseBuilder;

it('can determine if a request matches for the endpoint', function (string $endpointUrl, string $requestUrl, bool $matched) {
    $endpoint = EndpointBuilder::new()
        ->method('GET')
        ->uri($endpointUrl)
        ->make();
    $request = PsrRequestBuilder::new()
        ->method('GET')
        ->uri($requestUrl)
        ->make();

    $result = $endpoint->matches($request);

    expect($result)->toBe($matched);
})->with([
    ['/', '/', true],
    ['/', '', false],

    ['/something', '/something', true],
    ['/something', '/something?foo=bar', true],

    ['/something/', '/something/', true],
    ['/something/', '/something/?foo=bar', true],

    ['/something/', '/something', false],
    ['/something/', '/something?foo=bar', false],

    ['/something', '/something/', false],
    ['/something', '/something/?foo=bar', false],
]);

it('can return the relevant response checks for an endpoint', function () {
    $endpoint = EndpointBuilder::new()
        ->responses(
            ResponseBuilder::new()
                ->responseBody('application/json')
                ->status(200)
                ->make(),
        )
        ->make();
    $response = PsrResponseBuilder::new()
        ->header('Content-Type', 'application/json')
        ->status(200)
        ->make();

    $results = $endpoint->checksFor($response);

    expect($results)->toHaveCount(0);
});

it('can return the relevant request checks for an endpoint', function (Endpoint $endpoint, array $checks) {
    $request = PsrRequestBuilder::new()->make();

    $results = $endpoint->checksFor($request);

    foreach ($results as $result) {
        expect($result::class)->toBeIn($checks);
    }
})->with([
    'without parameters or request body' => [
        EndpointBuilder::new()->make(), [
            DeprecatedOperation::class,
        ],
    ],
    'with operation query parameters' => [
        EndpointBuilder::new()->deprecatedOperationQueryParameter('<query>')->make(),
        [
            DeprecatedOperation::class,
            DeprecatedParameters::class,
        ],
    ],
    'with path query parameters' => [
        EndpointBuilder::new()->deprecatedPathQueryParameter('<query>')->make(),
        [
            DeprecatedOperation::class,
            DeprecatedParameters::class,
        ],
    ],
    'with operation header parameters' => [
        EndpointBuilder::new()->deprecatedOperationHeaderParameter('<header>')->make(),
        [
            DeprecatedOperation::class,
            DeprecatedParameters::class,
        ],
    ],
    'with path header parameters' => [
        EndpointBuilder::new()->deprecatedPathHeader('<header>')->make(),
        [
            DeprecatedOperation::class,
            DeprecatedParameters::class,
        ],
    ],
    'with path parameters' => [
        EndpointBuilder::new()->deprecatedPathParameter('<path>')->make(),
        [
            DeprecatedOperation::class,
            DeprecatedParameters::class,
        ],
    ],
]);
