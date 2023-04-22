<?php

namespace jkniest\HueIt\Model;

use Illuminate\Support\Collection;

class Config
{
    /**
     * @param Collection<int, WhitelistDevice> $whitelist
     */
    public function __construct(
        private string $name,
        private int $zigBeeChannel,
        private string $modelId,
        private string $apiVersion,
        private bool $linkButtonPressed,
        private Collection $whitelist,
    )
    {
    }

    public static function fromResponse(array $data): self
    {
        $whitelist = (new Collection($data['whitelist']))
            ->map(static fn (array $device, string $id) => WhitelistDevice::fromResponse($id, $device))
            ->values();

        return new self(
            $data['name'],
            $data['zigbeechannel'],
            $data['modelid'],
            $data['apiversion'],
            $data['linkbutton'],
            $whitelist,
        );
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

    public function getWhitelist(): Whitelist
    {
        return $this->whitelist;
    }
}