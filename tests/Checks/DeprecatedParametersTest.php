<?php

namespace Tests\Checks;

use Apiboard\Checks\DeprecatedParameters;
use Apiboard\Checks\Results\Result;
use Tests\Builders\ContextBuilder;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\ParameterBuilder;
use Tests\Builders\PsrRequestBuilder;

function deprecatedParameters(...$args)
{
    return new DeprecatedParameters(...$args);
}

it('returns no results when there are no deprecated parameters used', function () {
    $request = PsrRequestBuilder::new()
        ->header('<header>', '<value>')
        ->query('<query>', '<value>')
        ->make();
    $endpoint = EndpointBuilder::new()
        ->parameters(
            ParameterBuilder::new()->deprecated(false)->query('<query>')->make(),
            ParameterBuilder::new()->deprecated(false)->path('<path>')->make(),
            ParameterBuilder::new()->deprecated(false)->header('<header>')->make(),
        )
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();
    $check = deprecatedParameters();
    $check->request($request);

    $context = $check->run($context);

    expect($context->results())->toBeEmpty();
});

it('returns results when there are deprecated query parameters used', function () {
    $request = PsrRequestBuilder::new()
        ->header('<header>', '<value>')
        ->query('<query>', '<value>')
        ->make();
    $endpoint = EndpointBuilder::new()
        ->parameters(
            ParameterBuilder::new()->deprecated(true)->query('<query>')->make(),
            ParameterBuilder::new()->deprecated(false)->path('<path>')->make(),
            ParameterBuilder::new()->deprecated(false)->header('<header>')->make(),
        )
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();
    $check = deprecatedParameters();
    $check->request($request);

    $context = $check->run($context);

    expect($context->results())->toHaveCount(1);
    expect($context->results()[0])->toBeInstanceOf(Result::class);
});

it('returns results when there are deprecated header parameters used', function () {
    $request = PsrRequestBuilder::new()
        ->header('<header>', '<value>')
        ->query('<query>', '<value>')
        ->make();
    $endpoint = EndpointBuilder::new()
        ->parameters(
            ParameterBuilder::new()->deprecated(false)->query('<query>')->make(),
            ParameterBuilder::new()->deprecated(false)->path('<path>')->make(),
            ParameterBuilder::new()->deprecated(true)->header('<header>')->make(),
        )
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();
    $check = deprecatedParameters();
    $check->request($request);

    $context = $check->run($context);

    expect($context->results())->toHaveCount(1);
    expect($context->results()[0])->toBeInstanceOf(Result::class);
});

it('returns results when there are deprecated path parameters used', function () {
    $request = PsrRequestBuilder::new()
        ->header('<header>', '<value>')
        ->query('<query>', '<value>')
        ->make();
    $endpoint = EndpointBuilder::new()
        ->parameters(
            ParameterBuilder::new()->deprecated(false)->query('<query>')->make(),
            ParameterBuilder::new()->deprecated(true)->path('<path>')->make(),
            ParameterBuilder::new()->deprecated(false)->header('<header>')->make(),
        )
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();
    $check = deprecatedParameters();
    $check->request($request);

    $context = $check->run($context);

    expect($context->results())->toHaveCount(1);
    expect($context->results()[0])->toBeInstanceOf(Result::class);
});
