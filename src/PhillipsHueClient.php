<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface PhillipsHueClient
{
    public function getIp(): string;

    public function getClient(): HttpClientInterface;

    public function useClient(HttpClientInterface $client): self;

    public function request(string $method, string $resource, ?array $body = null): array;

    public function userRequest(string $method, string $resource, ?array $body = null): array;

    public function lightRequest(Light $light, array $body): array;
}
