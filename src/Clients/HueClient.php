<?php

declare(strict_types=1);

namespace jkniest\HueIt\Clients;

interface HueClient
{
    public function isAuthenticated(): bool;

    public function authenticate(): void;

    public function get(string $endpoint): array;
}
