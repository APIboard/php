<?php

namespace Apiboard\Checks;

class Result
{
    protected string $severity;

    protected string $summary;

    protected array $details;

    public function __construct(string $severity, string $summary, array $details = [])
    {
        $this->severity = $severity;
        $this->summary = $summary;
        $this->details = $details;
    }

    public function severity(): string
    {
        return $this->severity;
    }

    public function summary(): string
    {
        return $this->summary;
    }

    public function details(): array
    {
        return $this->details;
    }
}
