<?php

namespace Apiboard\Reporting;

use Apiboard\OpenAPI\Structure\Operations;
use Apiboard\OpenAPI\Structure\Parameter;

interface ParameterUsage extends UsageReport
{
    public function parameter(): Parameter;

    public function operations(): Operations;
}
