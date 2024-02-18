<?php

namespace Tests\Checks;

use Apiboard\Checks\DeprecatedOperation;
use Apiboard\Checks\Result;
use Apiboard\OpenAPI\Endpoint;
use Psr\Http\Message\RequestInterface;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;

function deprecatedOperation(...$args)
{
    return new DeprecatedOperation(...$args);
}

it('returns no results when the operation is not deprecated', function () {
    $endpoint = EndpointBuilder::new()
        ->deprecated(false)
        ->make();

    $result = deprecatedOperation($endpoint)->run();

    expect($result)->toBeEmpty();
});

it('returns a result when the operation is deprecated and the message matches the endpoint', function () {
    $endpoint = EndpointBuilder::new()
        ->method('GET')
        ->uri('/foo/{bar}')
        ->deprecated(true)
        ->make();
    $request = PsrRequestBuilder::new()
        ->method('GET')
        ->uri('/foo/baz')
        ->make();
    $check = deprecatedOperation($endpoint);
    $check->message($request);

    $result = $check->run();

    expect($result)->toHaveCount(1);
    expect($result[0])->toBeInstanceOf(Result::class);
});

it(
    'returns no results when the operation is deprecated and the message does not match the endpoint',
    function (
        Endpoint $endpoint,
        RequestInterface $request,
    ) {
        $check = deprecatedOperation($endpoint);
        $check->message($request);

        $result = $check->run();

        expect($result)->toHaveCount(0);
    }
)->with([
    'different method' => [
        EndpointBuilder::new()
            ->deprecated()
            ->method('POST')
            ->make(),
        PsrRequestBuilder::new()
            ->method('GET')
            ->make(),
    ],
    'different uri' => [
        EndpointBuilder::new()
            ->deprecated()
            ->method('GET')
            ->uri('/foo/{bar}')
            ->make(),
        PsrRequestBuilder::new()
            ->method('GET')
            ->uri('/baz')
            ->make(),
    ],
]);
