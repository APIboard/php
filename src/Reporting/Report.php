<?php

namespace Apiboard\Reporting;

interface Report
{
    public function api(): string;

    public function hash(): string;
}
