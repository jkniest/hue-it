<?php

declare(strict_types=1);

namespace jkniest\HueIt\Fake\Model;

class FakeLight
{
    public string $id;

    public string $name;

    private function __construct()
    {
        $this->id = 'fake-id';
        $this->name = 'Fake Light';
    }

    public static function create(): self
    {
        return new self();
    }

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->id,
            'metadata' => [
                'name' => $this->name,
            ],
        ];
    }
}
