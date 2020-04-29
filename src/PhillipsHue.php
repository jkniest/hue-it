<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use jkniest\HueIt\Exceptions\PhillipsHueException;

class PhillipsHue
{
    private PhillipsHueClient $client;

    public function __construct(string $ip, ?string $username = null)
    {
        $this->client = new PhillipsHueClient($ip, $username);
    }

    public function getIp(): string
    {
        return $this->client->getIp();
    }

    public function getUsername(): ?string
    {
        return $this->client->getUsername();
    }

    public function getClient(): PhillipsHueClient
    {
        return $this->client;
    }

    public function useClient(PhillipsHueClient $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @throws PhillipsHueException
     */
    public function authenticate(string $deviceType): string
    {
        $result = $this->client->request('POST', '', ['devicetype' => $deviceType]);

        $username = $result[0]['success']['username'];
        $this->client->setUsername($username);

        return $username;
    }

    public function getConfig(): PhillipsHueConfig
    {
        $result = $this->client->userRequest('GET', 'config');

        return new PhillipsHueConfig($result);
    }
}
