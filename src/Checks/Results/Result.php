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

    protected array $hash;

    protected DateTime $createdAt;

    public function __construct(Api $api, Check $check, array $data, array $hash)
    {
        $this->api = $api;
        $this->check = $check;
        $this->data = $data;
        $this->hash = $hash;
        $this->createdAt = new DateTime();
    }

    public function reportId(): string
    {
        $hash = json_encode($this->hash);

        return md5("{$this->api->id()}:{$this->check->id()}:{$hash}");
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

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }
}
