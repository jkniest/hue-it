<?php

declare(strict_types=1);

namespace jkniest\HueIt\Fake;

use jkniest\HueIt\Clients\HueClient;
use jkniest\HueIt\Exceptions\NotAuthenticatedException;
use jkniest\HueIt\Fake\Model\FakeLight;

class FakeHueClient implements HueClient
{
    public const VALID_HOST = 'http://valid.hue';
    public const VALID_TOKEN = 'valid-fake-token';

    private bool $authenticated = false;

    /** @var FakeLight[] */
    private array $fakeLights = [];

    /**
     * @param FakeLight[] $lights
     */
    public function setFakeLights(array $lights): void
    {
        $this->fakeLights = $lights;
    }

    /**
     * @throws NotAuthenticatedException
     */
    public function get(string $endpoint): array
    {
        if(!$this->authenticated) {
            throw new NotAuthenticatedException();
        }

        switch ($endpoint) {
            case '/resource/light':
                return [
                    'data' => array_map(static fn ($light) => $light->toArray(), $this->fakeLights),
                ];

            default:
                return [];
        }
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    public function authenticate(): void
    {
        $this->authenticated = true;
    }
}
