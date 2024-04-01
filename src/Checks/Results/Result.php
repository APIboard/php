<?php

namespace Apiboard\Checks\Results;

use DateTime;

interface Result
{
    public function data(): array;

    public function loggedAt(): DateTime;
}
