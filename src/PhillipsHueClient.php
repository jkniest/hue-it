<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Symfony\Component\HttpClient\HttpClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

class PhillipsHueClient
{
    private string $ip;

    private ?string $username;

    private HttpClientInterface $client;

    public function __construct(string $ip, ?string $username = null)
    {
        $this->ip = $ip;
        $this->username = $username;
        $this->client = HttpClient::createForBaseUri('http://'.$ip);
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getUsername(): ?string
    {
        return $this->username;
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
     * @throws PhillipsHueException
     */
    public function request(string $method, string $resource, ?array $body = null): array
    {
        try {
            $result = $this->client->request($method, "/api/{$resource}", [
                'json' => $body,
            ])->toArray();

            if (isset($result[0]['error'])) {
                throw new PhillipsHueException($result[0]['error']['description'], $result[0]['error']['type']);
            }

            return $result;
        } catch (ClientExceptionInterface |
        DecodingExceptionInterface |
        RedirectionExceptionInterface |
        ServerExceptionInterface |
        TransportExceptionInterface $e) {
            throw new PhillipsHueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function userRequest(string $method, string $resource, ?array $body = null): array
    {
        return $this->request($method, "{$this->username}/{$resource}", $body);
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
}
