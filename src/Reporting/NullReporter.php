<?php

namespace Apiboard\Reporting;

class NullReporter implements Reporter
{
    public function reports(string ...$only): iterable
    {
        return [];
    }

    public function write(Report $report): void
    {
    }
}
