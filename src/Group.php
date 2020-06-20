<?php

declare(strict_types=1);

namespace jkniest\HueIt;

class Group
{
    private PhillipsHueClient $client;

    private int $id;

    private string $name;

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

    private function mapData(array $rawData): void
    {
        $this->name = $rawData['name'];
    }
}
