<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use jkniest\HueIt\Local\LocalHueClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;

/**
 * @property LocalHueClient $client
 */
class PhillipsHue extends PhillipsHueGateway
{
    public function __construct(string $ip, ?string $username = null)
    {
        parent::__construct(
            new LocalHueClient($ip, $username),
        );
    }

    public function getIp(): string
    {
        return $this->client->getIp();
    }

    public function getUsername(): ?string
    {
        return $this->client->getUsername();
    }

    public function getClient(): LocalHueClient
    {
        return $this->client;
    }

    public function useClient(LocalHueClient $client): self
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
}
