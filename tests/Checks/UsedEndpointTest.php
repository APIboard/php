<?php

namespace Tests\Checks;

use Apiboard\Checks\Results\Result;
use Apiboard\Checks\UsedEndpoint;
use Tests\Builders\ContextBuilder;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;

function usedEndpoint()
{
    return new UsedEndpoint();
}

it('returns a result when there is an endpoint', function () {
    $endpoint = EndpointBuilder::new()
        ->method('GET')
        ->uri('/foo/{bar}')
        ->make();
    $request = PsrRequestBuilder::new()
        ->method('GET')
        ->uri('/foo/baz')
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();
    $check = usedEndpoint();
    $check->request($request);

    $context = $check->run($context);

    expect($context->results())->toHaveCount(1);
    expect($context->results()[0])->toBeInstanceOf(Result::class);
});

it('returns no results when there is no endpoint', function () {
    $request = PsrRequestBuilder::new()
        ->method('GET')
        ->uri('/foo/baz')
        ->make();
    $context = ContextBuilder::new()
        ->make();
    $check = usedEndpoint();
    $check->request($request);

    $context = $check->run($context);

    expect($context->results())->toHaveCount(0);
});
