<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Illuminate\Support\Collection;
use jkniest\HueIt\Helper\ColorConverter;
use jkniest\HueIt\Exceptions\PhillipsHueException;

class Group implements IsControllable
{
    private string $name;

    /** @var Collection<int, int> */
    private Collection $lightIds;

    private string $type;

    private bool $allOn;

    private bool $anyOn;

    private ?string $class;

    private int $brightness;

    private int $colorTemperature;

    private int $saturation;

    private string $effect;

    private string $alert;

    private string $colorMode;

    private float $colorX;

    private float $colorY;

    public function __construct(
        private int $id,
        array $rawData,
        private PhillipsHueClient $client
    ) {
        $this->mapData($rawData);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, int>
     */
    public function getLightIds(): Collection
    {
        return $this->lightIds;
    }

    /**
     * @return Collection<int, Light>
     */
    public function getLights(): Collection
    {
        $result = $this->client->userRequest('GET', 'lights');

        return collect($result)
            ->map(fn (array $data, int $id) => new Light($id, $data, $this->client))
            ->filter(fn (Light $light) => null !== $this->lightIds->first(static fn (int $id) => $id === $light->getId()));
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function areAllOn(): bool
    {
        return $this->allOn;
    }

    public function isAnyOn(): bool
    {
        return $this->anyOn;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setOn(bool $on): self
    {
        $this->client->groupRequest($this, ['on' => $on]);

        $this->refresh();

        return $this;
    }

    public function turnOn(): IsControllable
    {
        return $this->setOn(true);
    }

    public function turnOff(): IsControllable
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
            $this->client->groupRequest($this, ['bri' => $value]);
            $this->refresh();

            return $this;
        }

        $absolute = (int) ceil(254 / 100 * $value);
        $this->client->groupRequest($this, ['bri' => $absolute]);
        $this->refresh();

        return $this;
    }

    public function getColorTemperature(bool $raw = true): int
    {
        if ($raw) {
            return $this->colorTemperature;
        }

        throw new PhillipsHueException(
            'Groups do currently not support color temperatures with percentage values.',
            -1,
        );
    }

    public function setColorTemperature(int $value, bool $raw = true): self
    {
        if (!$raw) {
            throw new PhillipsHueException(
                'Groups do currently not support color temperatures with percentage values.',
                -1,
            );
        }

        $this->client->groupRequest($this, ['ct' => $value]);
        $this->refresh();

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
            $this->client->groupRequest($this, ['sat' => $value]);
            $this->refresh();

            return $this;
        }

        $absolute = (int) ceil(254 / 100 * $value);
        $this->client->groupRequest($this, ['sat' => $absolute]);
        $this->refresh();

        return $this;
    }

    public function getEffect(): string
    {
        return $this->effect;
    }

    public function setEffect(string $effect): self
    {
        $this->client->groupRequest($this, ['effect' => $effect]);
        $this->refresh();

        return $this;
    }

    public function getAlert(): string
    {
        return $this->alert;
    }

    public function setAlert(string $value): self
    {
        $this->client->groupRequest($this, ['alert' => $value]);
        $this->refresh();

        return $this;
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
        $this->client->groupRequest($this, ['xy' => [$x, $y]]);
        $this->refresh();

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

        $this->client->groupRequest($this, ['xy' => $xy]);
        $this->refresh();

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

        $this->client->groupRequest($this, ['xy' => $xy]);
        $this->refresh();

        return $this;
    }

    public function refresh(): self
    {
        $rawData = $this->client->userRequest(
            'GET',
            "groups/{$this->id}",
        );

        $this->mapData($rawData);

        return $this;
    }

    private function mapData(array $rawData): void
    {
        $this->name = $rawData['name'];
        $this->lightIds = (new Collection($rawData['lights']))->map(static fn (string $id) => (int) $id);
        $this->type = $rawData['type'];
        $this->allOn = $rawData['state']['all_on'];
        $this->anyOn = $rawData['state']['any_on'];
        $this->class = $rawData['class'] ?? null;

        $this->brightness = $rawData['action']['bri'];
        $this->colorTemperature = $rawData['action']['ct'];
        $this->saturation = $rawData['action']['sat'];
        $this->effect = $rawData['action']['effect'];
        $this->alert = $rawData['action']['alert'];
        $this->colorMode = $rawData['action']['colormode'];
        $this->colorX = $rawData['action']['xy'][0];
        $this->colorY = $rawData['action']['xy'][1];
    }
}
