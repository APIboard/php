<?php

namespace Apiboard\Checks;

class DeprecatedQueryParameter extends Check
{
    public function __invoke(): void
    {
        foreach ($this->endpoint->parameters()?->inQuery() ?? [] as $query) {
            if ($query->deprecated() === false) {
                continue;
            }

            if (str_contains($this->request->getUri()->getQuery(), "{$query->name()}=")) {
                $this->endpoint->api()->log()->deprecatedParameterUsed($this->endpoint, $query);
            }
        }
    }
}
