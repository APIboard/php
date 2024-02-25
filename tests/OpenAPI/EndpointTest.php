<?php

namespace Tests\OpenAPI;

use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;

it('can determine if a request matches for the endpoint', function (string $endpointUrl, string $requestUrl, bool $matched) {
    $endpoint = EndpointBuilder::new()
        ->method('GET')
        ->uri($endpointUrl)
        ->make();
    $request = PsrRequestBuilder::new()
        ->method('GET')
        ->uri($requestUrl)
        ->make();

    $result = $endpoint->matches($request);

    expect($result)->toBe($matched);
})->with([
    ['/', '/', true],
    ['/', '', false],

    ['/something', '/something', true],
    ['/something', '/something?foo=bar', true],

    ['/something/', '/something/', true],
    ['/something/', '/something/?foo=bar', true],

    ['/something/', '/something', false],
    ['/something/', '/something?foo=bar', false],

    ['/something', '/something/', false],
    ['/something', '/something/?foo=bar', false],
]);
