<?php

namespace Tests\Reporting\Reports;

use Apiboard\Reporting\Reports\ParameterUsage;
use Tests\Builders\ContextBuilder;
use Tests\Builders\EndpointBuilder;
use Tests\Builders\ParameterUsedBuilder;

test('it can add a context from an empty state', function () {
    $report = ParameterUsage::fromState([]);
    $endpoint = EndpointBuilder::new()->make();
    $context = ContextBuilder::new()
        ->endpoint($endpoint)
        ->results(
            ParameterUsedBuilder::new()
                ->query('mahou')
                ->make(),
        )
        ->make();

    $report->include($context);

    expect($report->state())->toEqual([
        "{$context->api()->id()}:query:mahou" => [
            'api' => $context->api()->id(),
            'name' => 'mahou',
            'in' => 'query',
            'operations' => [
                [
                    'method' => $endpoint->operation()->method(),
                    'uri' => $endpoint->path()->uri(),
                ],
            ],
        ],
    ]);
});

test('it can add a context from a none empty state', function () {
    $endpointA = EndpointBuilder::new()
        ->method('POST')
        ->make();
    $endpointB = EndpointBuilder::new()
        ->method('GET')
        ->make();
    $context = ContextBuilder::new()
        ->endpoint($endpointB)
        ->results(
            ParameterUsedBuilder::new()
                ->query('mahou')
                ->make(),
        )
        ->make();
    $report = ParameterUsage::fromState([
        "{$context->api()->id()}:query:mahou" => [
            'api' => $context->api()->id(),
            'name' => 'mahou',
            'in' => 'query',
            'operations' => [
                [
                    'method' => $endpointA->operation()->method(),
                    'uri' => $endpointA->path()->uri(),
                ],
            ],
        ],
    ]);

    $report->include($context);

    expect($report->state())->toEqual([
        "{$context->api()->id()}:query:mahou" => [
            'api' => $context->api()->id(),
            'name' => 'mahou',
            'in' => 'query',
            'operations' => [
                [
                    'method' => $endpointA->operation()->method(),
                    'uri' => $endpointA->path()->uri(),
                ],
                [
                    'method' => $endpointB->operation()->method(),
                    'uri' => $endpointB->path()->uri(),
                ],
            ],
        ],
    ]);
});
