<?php

namespace jkniest\HueIt\Model;

use DateTimeImmutable;

class WhitelistDevice
{
    public function __construct(
        private string $id,
        private string $name,
        private DateTimeImmutable $lastUseDate,
        private DateTimeImmutable $createDate,
    )
    {
    }

    public static function fromResponse(string $id, array $data): self
    {
        return new self(
            $id,
            $data['name'],
            new DateTimeImmutable($data['last use date']),
            new DateTimeImmutable($data['create date']),
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastUseDate(): DateTimeImmutable
    {
        return $this->lastUseDate;
    }

    public function getCreateDate(): DateTimeImmutable
    {
        return $this->createDate;
    }
}