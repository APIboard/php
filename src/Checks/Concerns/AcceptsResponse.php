<?php

namespace Apiboard\Checks\Concerns;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;

trait AcceptsResponse
{
    protected ResponseInterface $response;

    public function message(MessageInterface $response): void
    {
        $this->response = $response;
    }
}
