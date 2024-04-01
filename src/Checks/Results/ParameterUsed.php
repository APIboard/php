<?php

namespace Apiboard\Checks\Results;

use Apiboard\OpenAPI\Structure\Parameter;
use DateTime;

class ParameterUsed implements Result
{
    protected Parameter $parameter;

    protected DateTime $loggedAt;

    public function __construct(Parameter $parameter)
    {
        $this->parameter = $parameter;
        $this->loggedAt = new DateTime();
    }

    public function data(): array
    {
        return $this->parameter->jsonSerialize();
    }

    public function loggedAt(): DateTime
    {
        return $this->loggedAt;
    }
}
