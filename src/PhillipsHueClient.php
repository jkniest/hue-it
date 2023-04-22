<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface PhillipsHueClient
{
    public function getClient(): HttpClientInterface;

    public function useClient(HttpClientInterface $client): self;

    public function rawRequest(string $method, string $resource, ?array $body = null, array $options = []): ResponseInterface;

    public function request(string $method, string $resource, ?array $body = null): array;

    public function v1Request(string $method, string $resource, ?array $body = null): array;

    public function v1UserRequest(string $method, string $resource, ?array $body = null): array;

    public function userRequest(string $method, string $resource, ?array $body = null): array;

    public function lightRequest(Light $light, array $body): array;

    public function groupRequest(Group $group, array $body): array;
}
