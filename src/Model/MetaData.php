<?php

namespace jkniest\HueIt\Model;

class MetaData
{
    public function __construct(
        private string $name
    )
    {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            $data['name']
        );
    }

    public function getName(): string
    {
        return $this->name;
    }
}