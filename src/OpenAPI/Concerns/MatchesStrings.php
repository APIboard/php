<?php

namespace Apiboard\OpenAPI\Concerns;

trait MatchesStrings
{
    protected function matchingUriPattern(string $source, string $target): bool
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $source);
        $pattern = "^$pattern$";

        return (bool) preg_match("#$pattern#", $target);
    }

    protected function matchingHttpMethod(string $source, string $target): bool
    {
        return strcasecmp($source, $target) === 0;
    }
}
