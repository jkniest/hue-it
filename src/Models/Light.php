<?php

declare(strict_types=1);

namespace jkniest\HueIt\Models;

class Light
{
    public readonly string $name;

    public function __construct(array $attributes)
    {
        $this->name = $attributes['metadata']['name'];
    }
}
