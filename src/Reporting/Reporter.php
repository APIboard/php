<?php

namespace Apiboard\Reporting;

interface Reporter
{
    /**
     * @return array<array-key,Report>
     */
    public function reports(string ...$ids): array;

    public function write(Report $report): void;
}
