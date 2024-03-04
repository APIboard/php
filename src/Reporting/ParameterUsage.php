<?php

namespace Apiboard\Reporting;

use Apiboard\OpenAPI\Endpoints;
use Apiboard\OpenAPI\Structure\Parameter;

class ParameterUsage extends UsageReport
{
    public function parameter(): Parameter
    {
        return new Parameter($this->data['parameter']);
    }

    public function endpoints(): Endpoints
    {
        return new Endpoints($this->data['endpoints']);
    }
}
