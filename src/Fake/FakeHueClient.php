<?php

declare(strict_types=1);

namespace jkniest\HueIt\Fake;

use jkniest\HueIt\Clients\HueClient;
use jkniest\HueIt\Fake\Model\FakeLight;

class FakeHueClient implements HueClient
{
    /** @var FakeLight[] */
    private array $fakeLights = [];

    /**
     * @param FakeLight[] $lights
     */
    public function setFakeLights(array $lights): void
    {
        $this->fakeLights = $lights;
    }

    public function get(string $endpoint): array
    {
        switch ($endpoint) {
            case '/resource/light':
                return [
                    'data' => array_map(static fn ($light) => $light->toArray(), $this->fakeLights),
                ];

            default:
                return [];
        }
    }
}
