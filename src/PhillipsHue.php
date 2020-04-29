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

class PhillipsHue
{
    private string $ip;

    private ?string $username;

    private HttpClientInterface $client;

    public function __construct(string $ip, ?string $username = null)
    {
        $this->ip = $ip;
        $this->username = $username;
        $this->client = HttpClient::createForBaseUri('http://'.$this->ip);
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
    public function authenticate(string $deviceType): string
    {
        try {
            $result = $this->client->request('POST', '/api', [
                'json' => ['devicetype' => $deviceType],
            ])->toArray();
        } catch (ClientExceptionInterface |
        DecodingExceptionInterface |
        RedirectionExceptionInterface |
        ServerExceptionInterface |
        TransportExceptionInterface $e) {
            throw new PhillipsHueException($e->getMessage(), $e->getCode(), $e);
        }

        if (isset($result[0]['error'])) {
            throw new PhillipsHueException($result[0]['error']['description'], $result[0]['error']['type']);
        }

        $username = $result[0]['success']['username'];
        $this->username = $username;

        return $username;
    }
}
