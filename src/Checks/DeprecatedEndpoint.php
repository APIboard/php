<?php

namespace Apiboard\Checks;

class DeprecatedEndpoint extends Check
{
    public function __invoke(): void
    {
        if ($this->endpoint->deprecated()) {
            $this->endpoint->api()->log()->deprecatedEndpointUsed($this->endpoint);
        }
    }
}
