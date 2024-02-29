<?php

namespace Apiboard\Reporting;

use DateTime;

interface UsageReport extends Report
{
    public function firstUsedAt(): DateTime;

    public function lastUsedAt(): DateTime;
}
