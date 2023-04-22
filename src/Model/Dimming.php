<?php

namespace jkniest\HueIt\Model;

class Dimming
{
    public function __construct(
        private float $brightness
    )
    {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            $data['brightness']
        );
    }

    public function getBrightness(): float
    {
        return $this->brightness;
    }
}