<?php

namespace Tests\Checks;

use Apiboard\Checks\DeprecatedPathParameter;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;

it('logs to APIboard when a request has a path parameter that is deprecated on the endpoint', function () {
    $request = PsrRequestBuilder::new()
        ->uri('/something/to-not-use-anymore')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->deprecatedPathParameter('deprecated')
        ->uri('/something/{deprecated}')
        ->make();

    (new DeprecatedPathParameter($request, $endpoint))->__invoke();

    $logger->assertWarning($endpoint, 'Deprecated path parameter was used.', [
        'parameter' => [
            'in' => 'path',
            'name' => 'deprecated',
            'deprecated' => true,
        ],
    ]);
});

it('does not log to APIboard when the request has a path parameter that is not deprecated on the endpoint', function () {
    $request = PsrRequestBuilder::new()
        ->uri('/something/maintained')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->uri('/something/{maintained}')
        ->make();

    (new DeprecatedPathParameter($request, $endpoint))->__invoke();

    $logger->assertEmpty();
});
