<?php

namespace Apiboard\Reporting;

use Apiboard\Reporting\Reports\Report;

class NullReporter implements Reporter
{
    public function reports(): array
    {
        return [];
    }

    public function write(Report $report): void
    {
    }
}
