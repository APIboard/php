<?php

namespace Apiboard\Checks\Results;

use Apiboard\Checks\Check;
use DateTime;

class Result
{
    protected Check $check;

    protected array $details;

    protected DateTime $date;

    private function __construct(Check $check, array $details)
    {
        $this->check = $check;
        $this->details = $details;
        $this->date = new DateTime();
    }

    public static function new(Check $check, array $details = []): self
    {
        return new self($check, $details);
    }

    public function check(): Check
    {
        return $this->check;
    }

    public function details(): array
    {
        return $this->details;
    }

    public function date(): DateTime
    {
        return $this->date;
    }
}
