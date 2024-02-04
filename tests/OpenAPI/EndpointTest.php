<?php

namespace Tests\OpenAPI;

use Apiboard\Checks\Checks;
use Apiboard\Checks\DeprecatedEndpoint;
use Apiboard\Checks\DeprecatedParameters;
use Apiboard\OpenAPI\Endpoint;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;
use Tests\Builders\PsrResponseBuilder;
use Tests\Builders\ResponseBuilder;

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

    expect($results)->toBeInstanceOf(Checks::class);
    expect($results->all())->toHaveCount(0);
});

it('can return the relevant request checks for an endpoint', function (Endpoint $endpoint, array $checks) {
    $request = PsrRequestBuilder::new()->make();

    $results = $endpoint->checksFor($request);

    expect($results)->toBeInstanceOf(Checks::class);
    expect($results->all())->toHaveCount(count($checks));
    foreach ($results->all() as $result) {
        expect($result::class)->toBeIn($checks);
    }
})->with([
    'without parameters or request body' => [
        EndpointBuilder::new()->make(), [
            DeprecatedEndpoint::class,
        ],
    ],
    'with operation query parameters' => [
        EndpointBuilder::new()->deprecatedOperationQueryParameter('<query>')->make(),
        [
            DeprecatedEndpoint::class,
            DeprecatedParameters::class,
        ],
    ],
    'with path query parameters' => [
        EndpointBuilder::new()->deprecatedPathQueryParameter('<query>')->make(),
        [
            DeprecatedEndpoint::class,
            DeprecatedParameters::class,
        ],
    ],
    'with operation header parameters' => [
        EndpointBuilder::new()->deprecatedOperationHeaderParameter('<header>')->make(),
        [
            DeprecatedEndpoint::class,
            DeprecatedParameters::class,
        ],
    ],
    'with path header parameters' => [
        EndpointBuilder::new()->deprecatedPathHeader('<header>')->make(),
        [
            DeprecatedEndpoint::class,
            DeprecatedParameters::class,
        ],
    ],
    'with path parameters' => [
        EndpointBuilder::new()->deprecatedPathParameter('<path>')->make(),
        [
            DeprecatedEndpoint::class,
            DeprecatedParameters::class,
        ],
    ],
]);
