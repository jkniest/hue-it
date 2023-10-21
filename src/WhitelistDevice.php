<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use DateTimeImmutable;

class WhitelistDevice
{
    private string $name;

    private DateTimeImmutable $lastUseDate;

    private DateTimeImmutable $createDate;

    public function __construct(
        private string $id,
        array $rawData,
    ) {
        $this->name = $rawData['name'];

        $this->lastUseDate = new DateTimeImmutable($rawData['last use date']);
        $this->createDate = new DateTimeImmutable($rawData['create date']);
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
