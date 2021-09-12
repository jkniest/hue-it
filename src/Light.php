<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use jkniest\HueIt\Helper\ColorConverter;

class Light implements IsControllable
{
    private string $name;

    private bool $on;

    private int $id;

    private int $brightness;

    private int $colorTemperature;

    private int $minColorTemperature;

    private int $maxColorTemperature;

    private int $saturation;

    private string $effect;

    private string $alert;

    private bool $reachable;

    private string $colorMode;

    private float $colorX;

    private float $colorY;

    private PhillipsHueClient $client;

    public function __construct(int $id, array $rawData, PhillipsHueClient $client)
    {
        $this->id = $id;
        $this->mapData($rawData);

        $this->client = $client;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isOn(): bool
    {
        return $this->on;
    }

    public function setOn(bool $on): self
    {
        $this->client->lightRequest($this, ['on' => $on]);

        $this->on = $on;

        return $this;
    }

    public function turnOn(): self
    {
        return $this->setOn(true);
    }

    public function turnOff(): self
    {
        return $this->setOn(false);
    }

    public function getBrightness(bool $raw = false): int
    {
        if ($raw) {
            return $this->brightness;
        }

        return (int) (100 / 254 * $this->brightness);
    }

    public function setBrightness(int $value, bool $raw = false): self
    {
        if ($raw) {
            $this->client->lightRequest($this, ['bri' => $value]);
            $this->brightness = $value;

            return $this;
        }

        $absolute = (int) ceil(254 / 100 * $value);
        $this->client->lightRequest($this, ['bri' => $absolute]);
        $this->brightness = $absolute;

        return $this;
    }

    public function getColorTemperature(bool $raw = false): int
    {
        if ($raw) {
            return $this->colorTemperature;
        }

        return (int) (
            100 / ($this->maxColorTemperature - $this->minColorTemperature)
            * ($this->colorTemperature - $this->minColorTemperature)
        );
    }

    public function setColorTemperature(int $value, bool $raw = false): self
    {
        if ($raw) {
            $this->client->lightRequest($this, ['ct' => $value]);
            $this->colorTemperature = $value;

            return $this;
        }

        $absolute = (int) (
            $value * ($this->maxColorTemperature - $this->minColorTemperature)
            / 100 + $this->minColorTemperature
        );

        $this->client->lightRequest($this, ['ct' => $absolute]);
        $this->colorTemperature = $absolute;

        return $this;
    }

    public function getSaturation(bool $raw = false): int
    {
        if ($raw) {
            return $this->saturation;
        }

        return (int) (100 / 254 * $this->saturation);
    }

    public function setSaturation(int $value, bool $raw = false): self
    {
        if ($raw) {
            $this->client->lightRequest($this, ['sat' => $value]);
            $this->saturation = $value;

            return $this;
        }

        $absolute = (int) ceil(254 / 100 * $value);
        $this->client->lightRequest($this, ['sat' => $absolute]);
        $this->saturation = $absolute;

        return $this;
    }

    public function getEffect(): string
    {
        return $this->effect;
    }

    public function setEffect(string $effect): self
    {
        $this->client->lightRequest($this, ['effect' => $effect]);

        $this->effect = $effect;

        return $this;
    }

    public function getAlert(): string
    {
        return $this->alert;
    }

    public function setAlert(string $value): self
    {
        $this->client->lightRequest($this, ['alert' => $value]);

        $this->alert = $value;

        return $this;
    }

    public function isReachable(): bool
    {
        return $this->reachable;
    }

    public function getColorMode(): string
    {
        return $this->colorMode;
    }

    public function getColorAsXY(): array
    {
        return [$this->colorX, $this->colorY];
    }

    public function setColorAsXY(float $x, float $y): self
    {
        $this->client->lightRequest($this, ['xy' => [$x, $y]]);

        $this->colorX = $x;
        $this->colorY = $y;

        return $this;
    }

    public function getColorAsRGB(): array
    {
        return ColorConverter::fromXYToRGB(
            $this->colorX,
            $this->colorY,
            $this->getBrightness(),
        );
    }

    public function setColorAsRGB(int $red, int $green, int $blue): self
    {
        $xy = ColorConverter::fromRGBToXY($red, $green, $blue);

        $this->client->lightRequest($this, ['xy' => $xy]);

        $this->colorX = $xy[0];
        $this->colorY = $xy[1];

        return $this;
    }

    public function getColorAsHex(): string
    {
        return ColorConverter::fromXYToHex(
            $this->colorX,
            $this->colorY,
            $this->getBrightness(),
        );
    }

    public function setColorAsHex(string $hex): self
    {
        $xy = ColorConverter::fromHexToXY($hex);

        $this->client->lightRequest($this, ['xy' => $xy]);

        $this->colorX = $xy[0];
        $this->colorY = $xy[1];

        return $this;
    }

    public function refresh(): self
    {
        $rawData = $this->client->userRequest(
            'GET',
            "lights/{$this->id}",
        );

        $this->mapData($rawData);

        return $this;
    }

    private function mapData(array $rawData): void
    {
        $this->name = $rawData['name'];
        $this->on = $rawData['state']['on'];
        $this->brightness = $rawData['state']['bri'];
        $this->colorTemperature = $rawData['state']['ct'];
        $this->minColorTemperature = $rawData['capabilities']['control']['ct']['min'];
        $this->maxColorTemperature = $rawData['capabilities']['control']['ct']['max'];
        $this->saturation = $rawData['state']['sat'];
        $this->effect = $rawData['state']['effect'];
        $this->alert = $rawData['state']['alert'];
        $this->reachable = $rawData['state']['reachable'];
        $this->colorMode = $rawData['state']['colormode'];
        $this->colorX = $rawData['state']['xy'][0];
        $this->colorY = $rawData['state']['xy'][1];
    }
}
