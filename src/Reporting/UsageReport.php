<?php

namespace Apiboard\Reporting;

use DateTime;

abstract class UsageReport extends Report
{
    public function lastUsedAt(): DateTime
    {
        return new DateTime($this->data['last_used_at']);
    }
}
