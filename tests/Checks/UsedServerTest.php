<?php

namespace Tests\Checks;

use Apiboard\Checks\Results\ServerUsed;
use Apiboard\Checks\UsedServer;
use Tests\Builders\ContextBuilder;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\PsrRequestBuilder;
use Tests\Builders\ServerBuilder;

function usedServer()
{
    return new UsedServer;
}

test('returns result with request domain when relative server is defined on endpoint', function () {
    $request = PsrRequestBuilder::new()
        ->uri('https://example.com/api/')
        ->make();
    $endpoint = EndpointBuilder::new()
        ->uri('/')
        ->servers(
            ServerBuilder::new()->url('/api')->make(),
        )
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();
    $check = usedServer();
    $check->request($request);

    $check->run($context);

    expect($context->results())->toHaveCount(1);
    expect($context->results()[0])->toBeInstanceOf(ServerUsed::class);
    expect($context->results()[0]->url())->toEqual('https://example.com/api');
});
