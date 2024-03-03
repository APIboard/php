<?php

namespace Apiboard\Reporting;

use Apiboard\OpenAPI\Structure\Operation;
use Apiboard\OpenAPI\Structure\Servers;
use DateTime;

class OperationUsage implements UsageReport
{
    protected string $id;

    protected string $api;

    protected Operation $operation;

    protected Servers $servers;

    protected DateTime $lastUsedAt;

    public function __construct(
        string $id,
        string $api,
        Operation $operation,
        Servers $servers,
        DateTime $lastUsedAt,
    ) {
        $this->id = $id;
        $this->api = $api;
        $this->operation = $operation;
        $this->servers = $servers;
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

    public function operation(): Operation
    {
        return $this->operation;
    }

    public function servers(): Servers
    {
        return $this->servers;
    }
}
