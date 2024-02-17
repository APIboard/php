<?php

namespace Apiboard\Checks\Concerns;

use Psr\Http\Message\MessageInterface;

trait AcceptsMessage
{
    protected MessageInterface $message;

    public function message(MessageInterface $message): void
    {
        $this->message = $message;
    }
}
