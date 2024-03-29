<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;
use jkniest\HueIt\Cloud\HueTokens;
use jkniest\HueIt\Cloud\CloudHueClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;

/**
 * @property CloudHueClient $client
 */
class PhillipsHueCloud extends PhillipsHueGateway
{
    private ?HueTokens $tokens = null;

    private ?string $username = null;

    public function __construct(
        private HueClient $connectionClient,
        private HueDevice $device,
        private string $appId,
    ) {
        parent::__construct(new CloudHueClient());
    }

    public function getClient(): CloudHueClient
    {
        return $this->client;
    }

    public function useClient(CloudHueClient $client): self
    {
        $this->client = $client;

        $this->client->setUsername($this->username);
        if ($this->tokens) {
            $this->client->setAccessToken($this->tokens->getAccessToken());
        }

        return $this;
    }

    public function getConnectionClient(): HueClient
    {
        return $this->connectionClient;
    }

    public function getOAuthUrl(string $state): string
    {
        $url = 'https://api.meethue.com/oauth2/auth'
            .'?clientid='.$this->connectionClient->getClientId()
            .'&appid='.$this->appId
            .'&deviceid='.$this->device->getId()
            .'&state='.$state
            .'&response_type=code';

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
            $this->connectionClient,
        );

        $this->tokens = new HueTokens(
            $tokens['access_token'] ?? '',
            $tokens['refresh_token'] ?? '',
            $this,
        );

        $this->client->setAccessToken($this->tokens->getAccessToken());

        return $this->tokens;
    }

    public function getTokens(): ?HueTokens
    {
        return $this->tokens;
    }

    public function useTokens(string $accessToken, string $refreshToken): self
    {
        $this->tokens = new HueTokens($accessToken, $refreshToken, $this);

        $this->client->setAccessToken($accessToken);

        return $this;
    }

    public function useUsername(string $username): self
    {
        $this->username = $username;
        $this->client->setUsername($username);

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function createUsername(): string
    {
        $this->client->authRequest('PUT', 'bridge/0/config', [
            'linkbutton' => true,
        ]);

        $response = $this->client->authRequest('POST', 'bridge', [
            'devicetype' => $this->device->getId(),
        ]);

        if (!isset($response[0]['success']['username'])) {
            throw new PhillipsHueException('No username returned.', -1);
        }

        $this->username = $response[0]['success']['username'] ?? '';
        $this->client->setUsername($this->username);

        return $this->username;
    }
}
