<?php

namespace Apiboard\Checks;

class DeprecatedPathParameter extends Check
{
    public function __invoke(): void
    {
        foreach ($this->endpoint->parameters()?->inPath() ?? [] as $path) {
            if ($path->deprecated()) {
                $this->endpoint->api()->log()->deprecatedParameterUsed($this->endpoint, $path);
            }
        }
    }
}
