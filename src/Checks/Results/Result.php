<?php

namespace Apiboard\Checks\Results;

use Apiboard\Checks\Check;
use Apiboard\OpenAPI\Structure\Structure;
use DateTime;

class Result
{
    protected Check $check;

    protected ?Structure $structure;

    protected DateTime $date;

    private function __construct(Check $check, ?Structure $structure)
    {
        $this->check = $check;
        $this->structure = $structure;
        $this->date = new DateTime();
    }

    public static function new(Check $check, ?Structure $structure = null): self
    {
        return new self($check, $structure);
    }

    public function check(): string
    {
        return get_class($this->check);
    }

    public function structure(): ?Structure
    {
        return $this->structure;
    }

    public function date(): DateTime
    {
        return $this->date;
    }
}
