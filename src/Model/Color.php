<?php

namespace jkniest\HueIt\Model;

class Color
{
    public function __construct(
        private float $x,
        private float $y,
    )
    {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            $data['xy']['x'],
            $data['xy']['y'],
        );
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }
}