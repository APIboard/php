<?php

namespace Tests\Checks;

use Apiboard\Checks\DeprecatedRequestBody;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;
use Tests\Builders\SchemaBuilder;

it('logs to APIboard when a request has a body that has a deprecated schema', function () {
    $request = PsrRequestBuilder::new()
        ->header('Content-Type', 'text/plain')
        ->body('Do not just use a string')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->requestBody(
            'text/plain',
            $schema = SchemaBuilder::new()->type('string')->deprecated(true)->make(),
        )
        ->make();

    (new DeprecatedRequestBody($request, $endpoint))->__invoke();

    $logger->assertWarning($endpoint, 'Deprecated text/plain request body schema was used.', [
        'media_type' => [
            'schema' => $schema->toArray(),
        ],
    ]);
});

it('logs to APIboard when a request has a body that has a partially deprecated object schema', function () {
    $request = PsrRequestBuilder::new()
        ->header('Content-Type', 'application/json')
        ->body('{"maintained": true, "deprecated": true}')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->requestBody(
            'application/json',
            $schema = SchemaBuilder::new()
                ->type('object')
                ->property('maintained', SchemaBuilder::new()->make())
                ->property('deprecated', SchemaBuilder::new()->deprecated()->make())
                ->make(),
        )
        ->make();

    (new DeprecatedRequestBody($request, $endpoint))->__invoke();

    $logger->assertWarning($endpoint, 'Deprecated application/json request body schema was used.', [
        'media_type' => [
            'schema' => $schema->toArray(),
        ],
    ]);
});

it('logs to APIboard when a request has a body that has a partially deprecated array schema', function () {
    $request = PsrRequestBuilder::new()
        ->header('Content-Type', 'application/json')
        ->body('[{"maintained": true, "deprecated": true}]')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->requestBody(
            'application/json',
            $schema = SchemaBuilder::new()
                ->type('array')
                ->items(
                    SchemaBuilder::new()
                        ->type('object')
                        ->property('maintained', SchemaBuilder::new()->make())
                        ->property('deprecated', SchemaBuilder::new()->deprecated()->make())
                        ->make(),
                )
                ->make(),
        )
        ->make();

    (new DeprecatedRequestBody($request, $endpoint))->__invoke();

    $logger->assertWarning($endpoint, 'Deprecated application/json request body schema was used.', [
        'media_type' => [
            'schema' => $schema->toArray(),
        ],
    ]);
});

it('does not log to APIboard when the request has a body that does not have any deprecated schemas', function () {
    $request = PsrRequestBuilder::new()
        ->header('Content-Type', 'text/plain')
        ->body('Do use a string')
        ->make();
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->requestBody(
            'text/plain',
            SchemaBuilder::new()->type('string')->make(),
        )
        ->make();

    (new DeprecatedRequestBody($request, $endpoint))->__invoke();

    $logger->assertEmpty();
});
