<?php

namespace Apiboard\Reporting;

use Apiboard\OpenAPI\Structure\Operations;
use Apiboard\OpenAPI\Structure\Parameter;
use DateTime;

class ParameterUsage implements UsageReport
{
    protected string $id;

    protected string $api;

    protected Parameter $parameter;

    protected Operations $operations;

    protected DateTime $lastUsedAt;

    public function __construct(
        string $id,
        string $api,
        Parameter $parameter,
        Operations $operations,
        DateTime $lastUsedAt,
    ) {
        $this->id = $id;
        $this->api = $api;
        $this->parameter = $parameter;
        $this->operations = $operations;
        $this->lastUsedAt = $lastUsedAt;
    }

    public function lastUsedAt(): DateTime
    {
        return $this->lastUsedAt;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function api(): string
    {
        return $this->api;
    }

    public function parameter(): Parameter
    {
        return $this->parameter;
    }

    public function operations(): Operations
    {
        return $this->operations;
    }
}
