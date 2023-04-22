<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use jkniest\HueIt\Local\LocalHueClient;

/**
 * @property LocalHueClient $client
 */
class PhillipsHue extends PhillipsHueGateway
{
    public function __construct(string $ip, ?string $applicationKey = null)
    {
        parent::__construct(
            new LocalHueClient($ip, $applicationKey),
        );
    }

    public function getIp(): string
    {
        return $this->client->getIp();
    }

    public function getApplicationKey(): ?string
    {
        return $this->client->getApplicationKey();
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

    public function authenticate(string $deviceType): string
    {
        $result = $this->client->v1Request('POST', '', ['devicetype' => $deviceType]);

        $applicationKey = $result[0]['success']['username'];
        $this->client->setApplicationKey($applicationKey);

        return $applicationKey;
    }
}
