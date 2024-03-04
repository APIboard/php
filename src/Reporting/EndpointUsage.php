<?php

namespace Apiboard\Reporting;

use Apiboard\OpenAPI\Structure\Operation;
use Apiboard\OpenAPI\Structure\PathItem;
use Apiboard\OpenAPI\Structure\Servers;

class EndpointUsage extends UsageReport
{
    public function path(): PathItem
    {
        return new PathItem($this->data['uri'], $this->data['path']);
    }

    public function operation(): Operation
    {
        return new Operation($this->data['method'], $this->data['operation']);
    }

    public function servers(): Servers
    {
        return new Servers($this->data['servers']);
    }
}
