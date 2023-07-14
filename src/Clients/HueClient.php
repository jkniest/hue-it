<?php

declare(strict_types=1);

namespace jkniest\HueIt\Clients;

interface HueClient
{
    public function get(string $endpoint): array;
}
