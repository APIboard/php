<?php

namespace Apiboard\Checks;

class DeprecatedHeaderParameter extends Check
{
    public function __invoke(): void
    {
        foreach ($this->endpoint->parameters()->inHeader() as $header) {
            if ($header->deprecated() === false) {
                continue;
            }

            if ($this->request->hasHeader($header->name())) {
                $this->endpoint->api()->log()->deprecatedParameterUsed($this->endpoint, $header);
            }
        }
    }
}
