<?php

namespace jkniest\HueIt\Model;

class ColorTemperature
{
    public function __construct(
        private ?int $colorTemperature,
        private int $minColorTemperature,
        private int $maxColorTemperature
    )
    {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            $data['mirek'],
            $data['mirek_schema']['mirek_minimum'],
            $data['mirek_schema']['mirek_maximum']
        );
    }

    public function getColorTemperature(): ?int
    {
        return $this->colorTemperature;
    }

    public function getMinColorTemperature(): int
    {
        return $this->minColorTemperature;
    }

    public function getMaxColorTemperature(): int
    {
        return $this->maxColorTemperature;
    }
}