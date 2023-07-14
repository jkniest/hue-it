<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use jkniest\HueIt\Models\Light;
use Illuminate\Support\Collection;
use jkniest\HueIt\Clients\HueClient;

class PhillipsHue
{
    private HueClient $client;

    public function setClient(HueClient $client): void
    {
        $this->client = $client;
    }

    public function getClient(): HueClient
    {
        return $this->client;
    }

    /**
     * @return Collection<string, Light>
     */
    public function getLights(): Collection
    {
        $lights = $this->client->get('/resource/light');

        return (new Collection($lights['data']))
            ->map(static fn (array $light) => new Light($light));
    }
}
