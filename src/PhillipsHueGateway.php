<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Illuminate\Support\Collection;
use jkniest\HueIt\Model\Config;

abstract class PhillipsHueGateway
{
    public function __construct(
        protected PhillipsHueClient $client
    ) {
    }

    public function getConfig(): Config
    {
        $result = $this->client->v1UserRequest('GET', 'config');

        return Config::fromResponse($result);
    }

    public function getLight(string $id): \jkniest\HueIt\Model\Light
    {
        $result = $this->client->userRequest('GET', "resource/light/{$id}");

        return \jkniest\HueIt\Model\Light::fromResponse($result['data'][0]);
    }

    /**
     * @return Collection<int, Light>
     */
    public function getAllLights(): Collection
    {
        $result = $this->client->userRequest('GET', 'resource/light');

        return collect($result['data'])
            ->map(fn (array $data) => \jkniest\HueIt\Model\Light::fromResponse($data));
    }

    public function getGroup(int $id): Group
    {
        $result = $this->client->userRequest('GET', "groups/{$id}");

        return new Group($id, $result, $this->client);
    }

    /**
     * @return Collection<int, Group>
     */
    public function getAllGroups(): Collection
    {
        $result = $this->client->userRequest('GET', 'groups');

        return collect($result)
            ->map(fn (array $data, int $id) => new Group($id, $data, $this->client));
    }
}
