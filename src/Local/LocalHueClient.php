<?php

declare(strict_types=1);

namespace jkniest\HueIt\Local;

use jkniest\HueIt\Group;
use jkniest\HueIt\Light;
use jkniest\HueIt\PhillipsHueClient;
use Symfony\Component\HttpClient\HttpClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

class LocalHueClient implements PhillipsHueClient
{
    private HttpClientInterface $client;

    public function __construct(
        private string $ip,
        private ?string $applicationKey = null
    ) {
        $this->client = HttpClient::createForBaseUri('https://'.$ip, [
            'verify_peer' => false,
            'verify_host' => false,
        ]);
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getApplicationKey(): ?string
    {
        return $this->applicationKey;
    }

    public function getClient(): HttpClientInterface
    {
        return $this->client;
    }

    public function useClient(HttpClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function rawRequest(string $method, string $resource, ?array $body = null, array $options = []): ResponseInterface
    {
        return $this->client->request($method, $resource, array_merge([
            'json' => $body,
        ], $options));
    }

    /**
     * @throws PhillipsHueException
     */
    public function request(string $method, string $resource, ?array $body = null, array $options = []): array
    {
        try {
            $result = $this->rawRequest($method, "/clip/v2/{$resource}", $body, $options)->toArray();

            if (isset($result[0]['error'])) {
                throw new PhillipsHueException($result[0]['error']['description'], $result[0]['error']['type']);
            }

            return $result;
        } catch (ClientExceptionInterface|
        DecodingExceptionInterface|
        RedirectionExceptionInterface|
        ServerExceptionInterface|
        TransportExceptionInterface $e) {
            throw new PhillipsHueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws PhillipsHueException
     */
    public function v1Request(string $method, string $resource, ?array $body = null, array $options = []): array
    {
        try {
            $result = $this->rawRequest($method, "/api/{$resource}", $body, $options)->toArray();

            if (isset($result[0]['error'])) {
                throw new PhillipsHueException($result[0]['error']['description'], $result[0]['error']['type']);
            }

            return $result;
        } catch (ClientExceptionInterface|
        DecodingExceptionInterface|
        RedirectionExceptionInterface|
        ServerExceptionInterface|
        TransportExceptionInterface $e) {
            throw new PhillipsHueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function userRequest(string $method, string $resource, ?array $body = null): array
    {
        return $this->request($method, $resource, $body, [
            'headers' => [
                'hue-application-key' => $this->applicationKey
            ]
        ]);
    }

    public function v1UserRequest(string $method, string $resource, ?array $body = null): array
    {
        return $this->v1Request($method, "{$this->applicationKey}/{$resource}", $body);
    }

    public function lightRequest(Light $light, array $body): array
    {
        return $this->userRequest('PUT', "lights/{$light->getId()}/state", $body);
    }

    public function groupRequest(Group $group, array $body): array
    {
        return $this->userRequest('PUT', "groups/{$group->getId()}/action", $body);
    }

    public function setApplicationKey(string $applicationKey): void
    {
        $this->applicationKey = $applicationKey;
    }
}
