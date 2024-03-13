<?php

namespace Apiboard\Reporting;

use Apiboard\Reporting\Reports\Report;

interface Reporter
{
    public function write(Report $report): void;
}
