<?php

namespace Apiboard\Reporting;

interface Report
{
    public function id(): string;

    public function api(): string;
}
