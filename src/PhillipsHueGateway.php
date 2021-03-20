<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Illuminate\Support\Collection;

abstract class PhillipsHueGateway
{
    protected PhillipsHueClient $client;

    public function __construct(PhillipsHueClient $client)
    {
        $this->client = $client;
    }

    public function getConfig(): PhillipsHueConfig
    {
        $result = $this->client->userRequest('GET', 'config');

        return new PhillipsHueConfig($result);
    }

    public function getLight(int $id): Light
    {
        $result = $this->client->userRequest('GET', "lights/{$id}");

        return new Light($id, $result, $this->client);
    }

    public function getAllLights(): Collection
    {
        $result = $this->client->userRequest('GET', 'lights');

        return collect($result)
            ->map(fn (array $data, int $id) => new Light($id, $data, $this->client));
    }

    public function getGroup(int $id): Group
    {
        $result = $this->client->userRequest('GET', "groups/{$id}");

        return new Group($id, $result, $this->client);
    }

    public function getAllGroups(): Collection
    {
        $result = $this->client->userRequest('GET', 'groups');

        return collect($result)
            ->map(fn (array $data, int $id) => new Group($id, $data, $this->client));
    }
}
