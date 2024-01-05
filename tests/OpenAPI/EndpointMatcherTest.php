<?php

namespace Tests\OpenAPI;

use Apiboard\OpenAPI\Endpoint;
use Apiboard\OpenAPI\EndpointMatcher;
use Tests\Builders\ApiBuilder;
use Tests\Builders\PsrRequestBuilder;

it('can match request to endpoint for operation without path parameters', function () {
    $request = PsrRequestBuilder::new()
        ->method('GET')
        ->uri('/no-path-parameters')
        ->make();

    $result = endpointMatcher()->matchingIn($request);

    expect($result)->toBeInstanceOf(Endpoint::class);
    expect($result->method())->toBe('GET');
    expect($result->url())->toBe('/no-path-parameters');
});

it('can match request to endpoint for operation with a path parameter', function () {
    $request = PsrRequestBuilder::new()
        ->method('POST')
        ->uri('/path/1234')
        ->make();

    $result = endpointMatcher()->matchingIn($request);

    expect($result)->toBeInstanceOf(Endpoint::class);
    expect($result->method())->toBe('POST');
    expect($result->url())->toBe('/path/{parameter}');
});

it('can match request to endpoint for operation with a server for the path', function () {
    $request = PsrRequestBuilder::new()
        ->method('GET')
        ->uri('/prefix/path')
        ->make();

    $result = endpointMatcher()->matchingIn($request);

    expect($result)->toBeInstanceOf(Endpoint::class);
    expect($result->method())->toBe('GET');
    expect($result->url())->toBe('/prefix/path');
});

it('can match request to endpoint for operation with a server for the operation', function () {
    $request = PsrRequestBuilder::new()
        ->method('GET')
        ->uri('/prefix/operation')
        ->make();

    $result = endpointMatcher()->matchingIn($request);

    expect($result)->toBeInstanceOf(Endpoint::class);
    expect($result->method())->toBe('GET');
    expect($result->url())->toBe('/prefix/operation');
});

it('does not match request to endpoint when no path can be matched', function () {
    $request = PsrRequestBuilder::new()
        ->method('GET')
        ->uri('/no-match')
        ->make();

    $result = endpointMatcher()->matchingIn($request);

    expect($result)->toBeNull();
});

it('does not match request to endpoint when no path operation can be matched', function () {
    $request = PsrRequestBuilder::new()
        ->method('PATCH')
        ->uri('/no-path-parameters')
        ->make();

    $result = endpointMatcher()->matchingIn($request);

    expect($result)->toBeNull();
});

function endpointMatcher(): EndpointMatcher
{
    return new EndpointMatcher(
        ApiBuilder::new()
            ->openapi(__DIR__.'/__fixtures__/endpoint-matcher.json')
            ->make(),
    );
}
