<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Illuminate\Support\Collection;

class Group
{
    private PhillipsHueClient $client;

    private int $id;

    private string $name;

    private Collection $lightIds;

    private string $type;

    private bool $allOn;

    private bool $anyOn;

    private string $class;

    public function __construct(int $id, array $rawData, PhillipsHueClient $client)
    {
        $this->id = $id;
        $this->mapData($rawData);

        $this->client = $client;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLightIds(): Collection
    {
        return $this->lightIds;
    }

    public function getLights(): Collection
    {
        $result = $this->client->userRequest('GET', 'lights');

        return collect($result)
            ->map(fn (array $data, int $id) => new Light($id, $data, $this->client))
            ->filter(fn (Light $light) => null !== $this->lightIds->first(static fn (int $id) => $id === $light->getId()));
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function areAllOn(): bool
    {
        return $this->allOn;
    }

    public function areAnyOn(): bool
    {
        return $this->anyOn;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    private function mapData(array $rawData): void
    {
        $this->name = $rawData['name'];
        $this->lightIds = collect($rawData['lights'])->map(static fn (string $id) => (int) $id);
        $this->type = $rawData['type'];
        $this->allOn = $rawData['state']['all_on'];
        $this->anyOn = $rawData['state']['any_on'];
        $this->class = $rawData['class'];
    }
}
