<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Illuminate\Support\Collection;

class PhillipsHueConfig
{
    private string $name;

    private int $zigBeeChannel;

    private string $modelId;

    private string $apiVersion;

    private bool $linkButtonPressed;

    /** @var Collection<int, WhitelistDevice> */
    private Collection $whitelist;

    private array $rawData;

    public function __construct(array $raw)
    {
        $this->rawData = $raw;

        $this->name = $raw['name'];
        $this->zigBeeChannel = $raw['zigbeechannel'];
        $this->modelId = $raw['modelid'];
        $this->apiVersion = $raw['apiversion'];
        $this->linkButtonPressed = $raw['linkbutton'];

        $this->whitelist = (new Collection($raw['whitelist']))
            ->map(static fn (array $device, string $key) => new WhitelistDevice($key, $device))
            ->values();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getZigBeeChannel(): int
    {
        return $this->zigBeeChannel;
    }

    public function getModelId(): string
    {
        return $this->modelId;
    }

    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    public function isLinkButtonPressed(): bool
    {
        return $this->linkButtonPressed;
    }

    /**
     * @return Collection<int, WhitelistDevice>
     */
    public function getWhitelist(): Collection
    {
        return $this->whitelist;
    }

    public function getRawData(): array
    {
        return $this->rawData;
    }
}
