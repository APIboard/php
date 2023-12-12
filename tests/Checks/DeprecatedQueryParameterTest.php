<?php

namespace Tests\Checks;

use Apiboard\Checks\DeprecatedQueryParameter;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;

it('logs to APIboard when a request has a query parameter that is deprecated on the endpoint', function () {
    $request = PsrRequestBuilder::new()
        ->query('deprecated', 'Yes')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->deprecatedPathQueryParameter('deprecated')
        ->make();

    (new DeprecatedQueryParameter($request, $endpoint))->__invoke();

    $logger->assertWarning($endpoint, 'Deprecated query parameter was used.', [
        'parameter' => [
            'in' => 'query',
            'name' => 'deprecated',
            'deprecated' => true,
        ],
    ]);
});

it('logs to APIboard when a request has a operation query parameter that is deprecated on the endpoint', function () {
    $request = PsrRequestBuilder::new()
        ->query('deprecated', 'Yes')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->deprecatedOperationQueryParameter('deprecated')
        ->make();

    (new DeprecatedQueryParameter($request, $endpoint))->__invoke();

    $logger->assertWarning($endpoint, 'Deprecated query parameter was used.', [
        'parameter' => [
            'in' => 'query',
            'name' => 'deprecated',
            'deprecated' => true,
        ],
    ]);
});

it('does not log to APIboard when the request has a query parameter that is not deprecated on the endpoint', function () {
    $request = PsrRequestBuilder::new()
        ->query('foo', 'bar')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->make();

    (new DeprecatedQueryParameter($request, $endpoint))->__invoke();

    $logger->assertEmpty();
});
