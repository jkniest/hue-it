<?php

declare(strict_types=1);

namespace jkniest\HueIt\Cloud;

class HueDevice
{
    public function __construct(
        private string $id,
        private ?string $name = null
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
