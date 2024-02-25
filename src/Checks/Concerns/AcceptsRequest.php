<?php

namespace Apiboard\Checks\Concerns;

use Psr\Http\Message\RequestInterface;

trait AcceptsRequest
{
    protected RequestInterface $request;

    public function request(RequestInterface $request): void
    {
        $this->request = $request;
    }
}
