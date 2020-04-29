<?php

declare(strict_types=1);

namespace jkniest\HueIt;

class Light
{
    private string $name;

    private bool $on;

    private int $id;

    private int $brightness;

    private PhillipsHueClient $client;

    public function __construct(int $id, array $rawData, PhillipsHueClient $client)
    {
        $this->id = $id;
        $this->name = $rawData['name'];
        $this->on = $rawData['state']['on'];
        $this->brightness = $rawData['state']['bri'];

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
        $this->client->userRequest('PUT', "lights/{$this->id}/state", [
            'on' => $on,
        ]);

        $this->on = $on;

        return $this;
    }

    public function turnOn(): void
    {
        $this->setOn(true);
    }

    public function turnOff(): void
    {
        $this->setOn(false);
    }

    public function getBrightness(bool $raw = false): int
    {
        if ($raw) {
            return $this->brightness;
        }

        return (int) (100 / 254 * $this->brightness);
    }
}
