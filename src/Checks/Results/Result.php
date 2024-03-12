<?php

namespace Apiboard\Checks\Results;

use Apiboard\Api;
use Apiboard\Checks\Check;
use DateTime;

class Result
{
    protected Api $api;

    protected Check $check;

    protected array $data;

    protected DateTime $loggedAt;

    public function __construct(Api $api, Check $check, array $data)
    {
        $this->api = $api;
        $this->check = $check;
        $this->data = $data;
        $this->loggedAt = new DateTime();
    }

    public function api(): Api
    {
        return $this->api;
    }

    public function check(): Check
    {
        return $this->check;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function loggedAt(): DateTime
    {
        return $this->loggedAt;
    }
}
