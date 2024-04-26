<?php

namespace Tests\Reporting\Reports;

use Apiboard\Reporting\Reports\OperationUsage;
use Tests\Builders\ContextBuilder;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\ServerUsedBuilder;

test('it can include a context from an empty state', function () {
    $report = OperationUsage::fromState([]);
    $endpoint = EndpointBuilder::new()->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->results(
            $serverUsed = ServerUsedBuilder::new()->make(),
        )
        ->make();

    $report->include($context);

    expect($report->state())->toEqual([
        $context->hash() => [
            'api' => $context->api()->id(),
            'method' => $endpoint->operation()->method(),
            'uri' => $endpoint->path()->uri(),
            'servers' => [
                $serverUsed->url(),
            ],
        ],
    ]);
});

test('it can include a context from a none empty state', function () {
    $report = OperationUsage::fromState([
        '::context-hash::' => [],
    ]);
    $endpoint = EndpointBuilder::new()->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->make();

    $report->include($context);

    expect($report->state())->toEqual([
        '::context-hash::' => [],
        $context->hash() => [
            'api' => $context->api()->id(),
            'method' => $endpoint->operation()->method(),
            'uri' => $endpoint->path()->uri(),
            'servers' => [],
        ],
    ]);
});
