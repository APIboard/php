<?php

namespace Tests\Http;

use Apiboard\Http\HeaderData;
use Apiboard\OpenAPI\Structure\Headers;
use ArrayAccess;
use IteratorAggregate;
use Tests\Builders\EndpointBuilder;

it('can be used as an array', function () {
    $headerData = new HeaderData([1, 2, 3]);
    $sum = 0;

    foreach ($headerData as $number) {
        $sum += $number;
    }

    expect(HeaderData::class)
        ->toImplement(ArrayAccess::class)
        ->toImplement(IteratorAggregate::class);
    expect($sum)->toEqual(6);
});

it('logs when a deprecated header is accessed', function () {
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->make();
    $headerData = new HeaderData(
        ['X-Deprecated' => ['Yes']],
        $endpoint,
        new Headers([
            'X-Deprecated' => [
                'deprecated' => true,
            ],
        ]),
    );

    $value = $headerData['X-Deprecated'];

    expect($value)->toBe(['Yes']);
    $logger->assertWarning($endpoint, 'Deprecated header[X-Deprecated] was used on response.', [
        'header' => [
            'deprecated' => true,
        ],
    ]);
});

it('does not logs when a maintained header is accessed', function () {
    $logger = arrayLogger();
    $endpoint = EndpointBuilder::new()
        ->logger($logger)
        ->make();
    $headerData = new HeaderData(
        ['X-Something' => ['Yes']],
        $endpoint,
        new Headers([
            'X-Something' => [
                'deprecated' => false,
            ],
        ]),
    );

    $value = $headerData['X-Something'];

    expect($value)->toBe(['Yes']);
    $logger->assertEmpty();
});
