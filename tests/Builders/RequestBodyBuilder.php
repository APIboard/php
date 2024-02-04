<?php

namespace Tests\Builders;

use Apiboard\OpenAPI\Structure\RequestBody;
use Apiboard\OpenAPI\Structure\Schema;

class RequestBodyBuilder extends Builder
{
    protected array $data = [
        'content' => [],
    ];

    public function json(Schema $schema): self
    {
        $this->data['content']['application/json'] = [
            'schema' => $schema->toArray(),
        ];

        return $this;
    }

    public function make(): RequestBody
    {
        return new RequestBody($this->data);
    }
}
