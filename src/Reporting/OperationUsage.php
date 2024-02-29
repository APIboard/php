<?php

namespace Apiboard\Reporting;

use Apiboard\OpenAPI\Structure\Operation;
use Apiboard\OpenAPI\Structure\Servers;

interface OperationUsage extends UsageReport
{
    public function operation(): Operation;

    public function servers(): Servers;
}
