<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use jkniest\HueIt\Clients\LocalHueClient;
use jkniest\HueIt\Models\Light;
use Illuminate\Support\Collection;
use jkniest\HueIt\Clients\HueClient;

class PhillipsHue
{
    public function __construct(
        private HueClient $client = new LocalHueClient()
    )
    {
    }

    public function setClient(HueClient $client): void
    {
        $this->client = $client;
    }

    public function getClient(): HueClient
    {
        return $this->client;
    }

    public function authenticate(string $host, string $token): self
    {
        $this->client->authenticate();

        return $this;
    }

    /**
     * @return Collection<string, Light>
     */
    public function getLights(): Collection
    {
        $lights = $this->client->get('/resource/light');

        return (new Collection($lights['data']))
            ->map(static fn(array $light) => new Light($light));
    }
}
