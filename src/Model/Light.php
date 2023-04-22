<?php

namespace jkniest\HueIt\Model;

class Light
{
    public function __construct(
        private string $id,
        private MetaData $metaData,
        private bool $on,
        private Dimming $dimming,
        private ColorTemperature $colorTemperature,
        private Color $color
    )
    {
    }

    public static function fromResponse(array $lightResponse): self
    {
        var_dump($lightResponse);
        die;
        return new self(
            $lightResponse['id'],
            MetaData::fromResponse($lightResponse['metadata']),
            $lightResponse['on']['on'],
            Dimming::fromResponse($lightResponse['dimming']),
            ColorTemperature::fromResponse($lightResponse['color_temperature']),
            Color::fromResponse($lightResponse['color'])
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMetaData(): MetaData
    {
        return $this->metaData;
    }

    public function isOn(): bool
    {
        return $this->on;
    }

    public function getDimming(): Dimming
    {
        return $this->dimming;
    }

    public function getColorTemperature(): ColorTemperature
    {
        return $this->colorTemperature;
    }

    public function getColor(): Color
    {
        return $this->color;
    }
}