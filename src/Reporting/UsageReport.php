<?php

namespace Apiboard\Reporting;

use DateTime;

interface UsageReport extends Report
{
    public function lastUsedAt(): DateTime;
}
