<?php

namespace Tests\Checks;

use Apiboard\Checks\DeprecatedEndpoint;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;

it('logs to APIboard when the endpoint is deprecated', function () {
    $request = PsrRequestBuilder::new()->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->deprecated(true)
        ->make();

    (new DeprecatedEndpoint($request, $endpoint))->__invoke();

    $logger->assertWarning($endpoint, 'Deprecated endpoint was used.');
});

it('does not log to APIboard when the endpoint is maintained', function () {
    $request = PsrRequestBuilder::new()->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->deprecated(false)
        ->make();

    (new DeprecatedEndpoint($request, $endpoint))->__invoke();

    $logger->assertEmpty();
});
