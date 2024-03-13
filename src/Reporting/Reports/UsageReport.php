<?php

namespace Apiboard\Reporting\Reports;

use DateTime;

interface UsageReport extends Report
{
    public function lastUsedAt(): DateTime;
}
