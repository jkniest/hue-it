<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Illuminate\Support\Collection;
use jkniest\HueIt\Local\LocalHueClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;

class PhillipsHue implements PhillipsHueGateway
{
    private LocalHueClient $client;

    public function __construct(string $ip, ?string $username = null)
    {
        $this->client = new LocalHueClient($ip, $username);
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
