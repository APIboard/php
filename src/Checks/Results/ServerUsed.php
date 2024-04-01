<?php

namespace Apiboard\Checks\Results;

use Apiboard\OpenAPI\Structure\Server;
use DateTime;

class ServerUsed implements Result
{
    protected Server $server;

    protected DateTime $loggedAt;

    public function __construct(Server $server)
    {
        $this->server = $server;
        $this->loggedAt = new DateTime();
    }

    public function data(): array
    {
        return $this->server->jsonSerialize();
    }

    public function loggedAt(): DateTime
    {
        return $this->loggedAt;
    }
}
