<?php

declare(strict_types=1);

namespace jkniest\HueIt\Cloud;

use jkniest\HueIt\Light;
use jkniest\HueIt\PhillipsHueClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CloudHueClient implements PhillipsHueClient
{
    private HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::createForBaseUri('https://api.meethue.com');
    }

    public function getClient(): HttpClientInterface
    {
        return $this->client;
    }

    public function useClient(HttpClientInterface $client): PhillipsHueClient
    {
        $this->client = $client;

        return $this;
    }

    public function rawRequest(string $method, string $resource, ?array $body = null, array $options = []): ResponseInterface
    {
        return $this->client->request($method, "/{$resource}", array_merge([
            'json' => $body,
        ], $options));
    }

    public function request(string $method, string $resource, ?array $body = null): array
    {
        throw new \LogicException('Not implemented.');
    }

    public function userRequest(string $method, string $resource, ?array $body = null): array
    {
        throw new \LogicException('Not implemented.');
    }

    public function lightRequest(Light $light, array $body): array
    {
        throw new \LogicException('Not implemented.');
    }
}
