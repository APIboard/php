<?php

namespace Tests\Checks;

use Apiboard\Checks\DeprecatedHeaderParameter;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;

it('logs to APIboard when a request has a path header that is deprecated on the endpoint', function () {
    $request = PsrRequestBuilder::new()
        ->header('Deprecated', 'Yes')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->deprecatedPathHeader('Deprecated')
        ->make();

    (new DeprecatedHeaderParameter($request, $endpoint))->__invoke();

    $logger->assertWarning($endpoint, 'Deprecated header parameter was used.', [
        'parameter' => [
            'in' => 'header',
            'name' => 'Deprecated',
            'deprecated' => true,
        ],
    ]);
});

it('logs to APIboard when a request has a operation header that is deprecated on the endpoint', function () {
    $request = PsrRequestBuilder::new()
        ->header('Deprecated', 'Yes')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->deprecatedOperationHeaderParameter('Deprecated')
        ->make();

    (new DeprecatedHeaderParameter($request, $endpoint))->__invoke();

    $logger->assertWarning($endpoint, 'Deprecated header parameter was used.', [
        'parameter' => [
            'in' => 'header',
            'name' => 'Deprecated',
            'deprecated' => true,
        ],
    ]);
});

it('does not log to APIboard when the request has a header that is not deprecated on the endpoint', function () {
    $request = PsrRequestBuilder::new()
        ->header('Foo', 'bar')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->make();

    (new DeprecatedHeaderParameter($request, $endpoint))->__invoke();

    $logger->assertEmpty();
});
