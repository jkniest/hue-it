<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Illuminate\Support\Collection;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;

class PhillipsHueCloud implements PhillipsHueGateway
{
    private HueClient $client;

    private HueDevice $device;

    private string $appId;

    public function __construct(HueClient $client, HueDevice $device, string $appId)
    {
        $this->client = $client;
        $this->device = $device;
        $this->appId = $appId;
    }

    public function getOAuthUrl(string $state): string
    {
        $url = 'https://api.meethue.com/oauth2/auth'.
            '?clientid='.$this->client->getClientId().
            '&appid='.$this->appId.
            '&deviceid='.$this->device->getId().
            '&state='.$state.
            '&response_type=code';

        if (null !== $this->device->getName()) {
            $url .= '&devicename='.$this->device->getName();
        }

        return $url;
    }

    public function getConfig(): PhillipsHueConfig
    {
        throw new \LogicException('No');
    }

    public function getLight(int $id): Light
    {
        throw new \LogicException('No');
    }

    public function getAllLights(): Collection
    {
        throw new \LogicException('No');
    }
}
