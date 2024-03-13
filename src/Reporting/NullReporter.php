<?php

namespace Apiboard\Reporting;

use Apiboard\Reporting\Reports\Report;

class NullReporter implements Reporter
{
    public function write(Report $report): void
    {
    }
}
