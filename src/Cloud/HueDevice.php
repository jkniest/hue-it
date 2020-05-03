<?php

declare(strict_types=1);

namespace jkniest\HueIt\Cloud;

class HueDevice
{
    private string $id;

    private ?string $name;

    public function __construct(string $id, ?string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
