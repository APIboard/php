<?php

namespace Tests\Checks;

use Apiboard\Checks\Results\ParameterUsed;
use Apiboard\Checks\UsedParameters;
use Tests\Builders\ContextBuilder;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\ParameterBuilder;
use Tests\Builders\PsrRequestBuilder;

function usedParameters()
{
    return new UsedParameters();
}

it('returns no results when there are no query or header parameters used', function () {
    $request = PsrRequestBuilder::new()
        ->make();
    $endpoint = EndpointBuilder::new()
        ->parameters(
            ParameterBuilder::new()->query('<query>')->make(),
            ParameterBuilder::new()->header('<header>')->make(),
        )
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();
    $check = usedParameters();
    $check->request($request);

    $context = $check->run($context);

    expect($context->results())->toBeEmpty();
});

it('returns results when there are query parameters used', function () {
    $request = PsrRequestBuilder::new()
        ->query('<query>', '<value>')
        ->make();
    $endpoint = EndpointBuilder::new()
        ->parameters(
            ParameterBuilder::new()->query('<query>')->make(),
            ParameterBuilder::new()->header('<header>')->make(),
        )
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();
    $check = usedParameters();
    $check->request($request);

    $context = $check->run($context);

    expect($context->results())->toHaveCount(1);
    expect($context->results()[0])->toBeInstanceOf(ParameterUsed::class);
});

it('returns results when there are header parameters used', function () {
    $request = PsrRequestBuilder::new()
        ->header('<header>', '<value>')
        ->make();
    $endpoint = EndpointBuilder::new()
        ->parameters(
            ParameterBuilder::new()->query('<query>')->make(),
            ParameterBuilder::new()->header('<header>')->make(),
        )
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();
    $check = usedParameters();
    $check->request($request);

    $context = $check->run($context);

    expect($context->results())->toHaveCount(1);
    expect($context->results()[0])->toBeInstanceOf(ParameterUsed::class);
});

it('returns results when there are path parameters defined on the endpoint', function () {
    $request = PsrRequestBuilder::new()->make();
    $endpoint = EndpointBuilder::new()
        ->parameters(
            ParameterBuilder::new()->query('<query>')->make(),
            ParameterBuilder::new()->path('<path>')->make(),
            ParameterBuilder::new()->header('<header>')->make(),
        )
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();
    $check = usedParameters();
    $check->request($request);

    $context = $check->run($context);

    expect($context->results())->toHaveCount(1);
    expect($context->results()[0])->toBeInstanceOf(ParameterUsed::class);
});
