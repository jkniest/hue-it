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

    public function __construct(
        private array $rawData
    ) {
        $this->name = $this->rawData['name'];
        $this->zigBeeChannel = $this->rawData['zigbeechannel'];
        $this->modelId = $this->rawData['modelid'];
        $this->apiVersion = $this->rawData['apiversion'];
        $this->linkButtonPressed = $this->rawData['linkbutton'];

        $this->whitelist = (new Collection($this->rawData['whitelist']))
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
