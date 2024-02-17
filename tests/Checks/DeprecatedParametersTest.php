<?php

namespace Tests\Checks;

use Apiboard\Checks\DeprecatedParameters;
use Apiboard\Checks\Result;
use Apiboard\OpenAPI\Structure\Parameters;
use Tests\Builders\ParameterBuilder;
use Tests\Builders\PsrRequestBuilder;

function deprecatedParameters(...$args)
{
    return new DeprecatedParameters(...$args);
}

it('returns no results when there are no deprecated parameters used', function () {
    $message = PsrRequestBuilder::new()
        ->header('<header>', '<value>')
        ->query('<query>', '<value>')
        ->make();
    $parameters = new Parameters([
        ParameterBuilder::new()->query('<query>')->make(),
        ParameterBuilder::new()->path('<path>')->make(),
        ParameterBuilder::new()->header('<header>')->make(),
    ]);
    $check = deprecatedParameters($parameters);
    $check->message($message);

    $result = $check->run();

    expect($result)->toBeEmpty();
});

it('returns results when there are deprecated query parameters used', function () {
    $message = PsrRequestBuilder::new()
        ->header('<header>', '<value>')
        ->query('<query>', '<value>')
        ->make();
    $parameters = new Parameters([
        ParameterBuilder::new()->deprecated(true)->query('<query>')->make(),
        ParameterBuilder::new()->deprecated(false)->path('<path>')->make(),
        ParameterBuilder::new()->deprecated(false)->header('<header>')->make(),
    ]);
    $check = deprecatedParameters($parameters);
    $check->message($message);

    $result = $check->run();

    expect($result)->toHaveCount(1);
    expect($result[0])->toBeInstanceOf(Result::class);
});

it('returns results when there are deprecated header parameters used', function () {
    $message = PsrRequestBuilder::new()
        ->header('<header>', '<value>')
        ->query('<query>', '<value>')
        ->make();
    $parameters = new Parameters([
        ParameterBuilder::new()->deprecated(false)->query('<query>')->make(),
        ParameterBuilder::new()->deprecated(false)->path('<path>')->make(),
        ParameterBuilder::new()->deprecated(true)->header('<header>')->make(),
    ]);
    $check = deprecatedParameters($parameters);
    $check->message($message);

    $result = $check->run();

    expect($result)->toHaveCount(1);
    expect($result[0])->toBeInstanceOf(Result::class);
});

it('returns results when there are deprecated path parameters used', function () {
    $message = PsrRequestBuilder::new()
        ->header('<header>', '<value>')
        ->query('<query>', '<value>')
        ->make();
    $parameters = new Parameters([
        ParameterBuilder::new()->deprecated(false)->query('<query>')->make(),
        ParameterBuilder::new()->deprecated(true)->path('<path>')->make(),
        ParameterBuilder::new()->deprecated(false)->header('<header>')->make(),
    ]);
    $check = deprecatedParameters($parameters);
    $check->message($message);

    $result = $check->run();

    expect($result)->toHaveCount(1);
    expect($result[0])->toBeInstanceOf(Result::class);
});
