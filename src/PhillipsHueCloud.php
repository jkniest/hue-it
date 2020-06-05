<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Illuminate\Support\Collection;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;
use jkniest\HueIt\Cloud\HueTokens;
use jkniest\HueIt\Cloud\CloudHueClient;

class PhillipsHueCloud implements PhillipsHueGateway
{
    private CloudHueClient $client;

    private HueClient $connectionClient;

    private HueDevice $device;

    private string $appId;

    private ?HueTokens $tokens = null;

    public function __construct(HueClient $connectionClient, HueDevice $device, string $appId)
    {
        $this->connectionClient = $connectionClient;
        $this->device = $device;
        $this->appId = $appId;
        $this->client = new CloudHueClient();
    }

    public function getClient(): CloudHueClient
    {
        return $this->client;
    }

    public function useClient(CloudHueClient $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getConnectionClient(): HueClient
    {
        return $this->connectionClient;
    }

    public function getOAuthUrl(string $state): string
    {
        $url = 'https://api.meethue.com/oauth2/auth'.
            '?clientid='.$this->connectionClient->getClientId().
            '&appid='.$this->appId.
            '&deviceid='.$this->device->getId().
            '&state='.$state.
            '&response_type=code';

        if (null !== $this->device->getName()) {
            $url .= '&devicename='.$this->device->getName();
        }

        return $url;
    }

    public function authenticate(string $code): HueTokens
    {
        $tokens = $this->client->handleDigestAuth(
            "oauth2/token?code={$code}&grant_type=authorization_code",
            '/oauth2/token',
            $this->connectionClient
        );

        return $this->tokens = new HueTokens(
            $tokens['access_token'] ?? '',
            $tokens['refresh_token'] ?? '',
            $this
        );
    }

    public function getTokens(): ?HueTokens
    {
        return $this->tokens;
    }

    public function useTokens(string $accessToken, string $refreshToken): self
    {
        $this->tokens = new HueTokens($accessToken, $refreshToken, $this);

        return $this;
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
