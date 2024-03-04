<?php

namespace Apiboard\Reporting;

abstract class Report
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function id(): string
    {
        return $this->data['id'];
    }

    public function api(): string
    {
        return $this->data['api'];
    }
}
