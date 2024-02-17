<?php

namespace Apiboard\Checks\Concerns;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;

trait AcceptsRequest
{
    protected RequestInterface $request;

    public function message(MessageInterface $message): void
    {
        $this->request = $message;
    }
}
