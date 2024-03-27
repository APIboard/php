<?php

namespace Apiboard\Checks\Results;

use DateTime;

class Result
{
    protected string $check;

    protected array $data;

    protected DateTime $loggedAt;

    public function __construct(string $check, array $data)
    {
        $this->check = $check;
        $this->data = $data;
        $this->loggedAt = new DateTime();
    }

    public function check(): string
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
