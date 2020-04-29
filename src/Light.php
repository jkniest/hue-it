<?php

declare(strict_types=1);

namespace jkniest\HueIt;

class Light
{
    private string $name;

    public function __construct(array $rawData)
    {
        $this->name = $rawData['name'];
    }

    public function getName(): string
    {
        return $this->name;
    }
}
