<?php

namespace Tests\Http;

use Apiboard\Http\ResponseData;
use Apiboard\OpenAPI\Endpoint;
use Apiboard\OpenAPI\Structure\Schema;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\SchemaBuilder;

it('can be constructed with an endpoint and schema', function () {
    $endpoint = EndpointBuilder::new()->make();
    $schema = SchemaBuilder::new()->make();

    $result = new ResponseData([], $endpoint, $schema);

    expect($result)->toBeInstanceOf(ResponseData::class);
    expect($result->endpoint())->toBeInstanceOf(Endpoint::class);
    expect($result->schema())->toBeInstanceOf(Schema::class);
});

it('can be used as an object when constructed with an object', function () {
    $data = (object) [
        'property' => 'value',
    ];

    $responseData = new ResponseData($data);

    expect($responseData->property)->toBe('value');
});

it('can be used as an object or array when constructed with an associative array', function () {
    $data = [
        'property' => 'value',
    ];

    $responseData = new ResponseData($data);

    expect($responseData->property)->toBe('value');
    expect($responseData['property'])->toBe('value');
});

it('can be used as an array when constructed with a regular array', function () {
    $data = [
        'value',
    ];

    $responseData = new ResponseData($data);

    expect($responseData[0])->toBe('value');
});

it('returns a new instance of itself when the accessed data is an object', function () {
    $data = [
        'object' => [
            'property' => 'value',
        ],
    ];

    $responseData = new ResponseData($data);

    expect($responseData->object)->toBeInstanceOf(ResponseData::class);
    expect($responseData->object->property)->toBe('value');
});

it('returns a new instance of itself when the accessed data is an array of objects', function () {
    $data = [
        'object' => [
            [
                'property' => 'value',
            ],
        ],
    ];

    $responseData = new ResponseData($data);

    expect($responseData->object)->toBeInstanceOf(ResponseData::class);
    expect($responseData->object[0]->property)->toBe('value');
});

it('constructs with the schema of the accessed property', function () {
    $endpoint = EndpointBuilder::new()->make();
    $nestedSchema = SchemaBuilder::new()
        ->type('object')
        ->property(
            'nested_property',
            SchemaBuilder::new()->type('string')->make(),
        )
        ->make();
    $schema = SchemaBuilder::new()
        ->type('object')
        ->property('property', $nestedSchema)
        ->make();
    $data = [
        'property' => [
            'nested_property' => 'value',
        ],
    ];

    $responseData = new ResponseData($data, $endpoint, $schema);

    expect($responseData->property->schema())->toBeInstanceOf(Schema::class);
    expect($responseData->property->schema()->properties()['nested_property'])->not->toBeNull();
});

it('logs when a deprecated property is accessed', function () {
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->make();
    $schema = SchemaBuilder::new()
        ->type('object')
        ->property(
            'property',
            $deprecatedSchema = SchemaBuilder::new()->deprecated()->type('string')->make(),
        )
        ->make();
    $data = [
        'property' => 'value',
    ];
    $responseData = new ResponseData($data, $endpoint, $schema);

    $responseData->property;

    $logger->assertWarning($endpoint, 'Deprecated string schema was used.', [
        'schema' => $deprecatedSchema->toArray(),
    ]);
});

it('does not log when a maintained property is accessed', function () {
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->make();
    $schema = SchemaBuilder::new()
        ->type('object')
        ->property(
            'property',
            SchemaBuilder::new()->make(),
        )
        ->make();
    $data = [
        'property' => 'value',
    ];
    $responseData = new ResponseData($data, $endpoint, $schema);

    $responseData->property;

    $logger->assertEmpty();
});
