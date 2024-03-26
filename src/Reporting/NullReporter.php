<?php

namespace Apiboard\Reporting;

class NullReporter implements Reporter
{
    public function reports(string ...$ids): array
    {
        return [];
    }

    public function write(Report $report): void
    {
    }
}
