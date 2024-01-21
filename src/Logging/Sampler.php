<?php

namespace Apiboard\Logging;

use Closure;

class Sampler
{
    protected int|float $rate;

    protected Closure $callback;

    public function __construct(int|float $rate, Closure $callback)
    {
        $this->rate = $rate;
        $this->callback = $callback;
    }

    public function __invoke(...$args): void
    {
        if ($this->shouldSample()) {
            ($this->callback)(...$args);
        }
    }

    public function shouldSample(): bool
    {
        $rate = random_int(0, PHP_INT_MAX) / PHP_INT_MAX;

        return $rate <= $this->rate;
    }
}
