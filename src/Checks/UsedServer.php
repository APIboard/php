<?php

namespace Apiboard\Checks;

use Apiboard\Checks\Concerns\AcceptsRequest;
use Apiboard\Checks\Results\ServerUsed;
use Apiboard\Context;
use Apiboard\OpenAPI\Concerns\MatchesStrings;
use Apiboard\OpenAPI\Structure\Server;

class UsedServer implements Check
{
    use AcceptsRequest;
    use MatchesStrings;

    public function run(Context $context): Context
    {
        $endpoint = $context->endpoint();

        if ($endpoint?->servers() === null) {
            return $context;
        }

        foreach ($endpoint->servers() as $server) {
            $serverUrl = $this->serverUrl($server);

            $usedServer = $this->matchingUriPattern(
                "{$serverUrl}{$endpoint->path()->uri()}",
                $this->request->getUri()->__toString(),
            );

            if ($usedServer) {
                $context->add(new ServerUsed($serverUrl));
            }
        }

        return $context;
    }

    protected function serverUrl(Server $server): string
    {
        $url = $server->url();

        if (str_starts_with($url, '/')) {
            return $this->request->getUri()->withPath($url)->__toString();
        }

        return $url;
    }
}
