<?php

namespace Apiboard\Reporting;

interface Reporter
{
    /**
     * @param  class-string<Report>[]  ...$only
     * @return iterable<array-key,Report>
     */
    public function reports(string ...$only): iterable;

    public function write(Report $report): void;
}
