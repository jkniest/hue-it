<?php

declare(strict_types=1);

namespace jkniest\HueIt;

interface IsControllable
{
    public function setOn(bool $on): self;

    public function turnOn(): self;

    public function turnOff(): self;

    public function getBrightness(bool $raw = false): int;

    public function setBrightness(int $value, bool $raw = false): self;

    public function getColorTemperature(bool $raw = false): int;

    public function setColorTemperature(int $value, bool $raw = false): self;

    public function getSaturation(bool $raw = false): int;

    public function setSaturation(int $value, bool $raw = false): self;

    public function getEffect(): string;

    public function setEffect(string $effect): self;

    public function getAlert(): string;

    public function setAlert(string $value): self;

    public function getColorMode(): string;

    public function getColorAsXY(): array;

    public function setColorAsXY(float $x, float $y): self;

    public function getColorAsRGB(): array;

    public function setColorAsRGB(int $red, int $green, int $blue): self;

    public function getColorAsHex(): string;

    public function setColorAsHex(string $hex): self;
}
