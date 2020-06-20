<?php

declare(strict_types=1);

namespace jkniest\HueIt\Cloud;

use jkniest\HueIt\Light;
use jkniest\HueIt\PhillipsHueClient;
use Symfony\Component\HttpClient\HttpClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

class CloudHueClient implements PhillipsHueClient
{
    private HttpClientInterface $client;

    private ?string $username;

    private ?string $accessToken;

    public function __construct(?string $username = null, ?string $accessToken = null)
    {
        $this->client = HttpClient::createForBaseUri('https://api.meethue.com');
        $this->username = $username;
        $this->accessToken = $accessToken;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): void
    {
        $this->accessToken = $accessToken;
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

    public function request(string $method, string $resource, ?array $body = null, array $options = []): array
    {
        try {
            $result = $this->rawRequest($method, $resource, $body, $options)->toArray();

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

    public function authRequest(string $method, string $resource, ?array $body = null): array
    {
        return $this->request($method, $resource, $body, [
            'auth_bearer' => $this->accessToken ?? '',
        ]);
    }

    public function userRequest(string $method, string $resource, ?array $body = null): array
    {
        return $this->authRequest($method, "bridge/{$this->username}/{$resource}", $body);
    }

    public function lightRequest(Light $light, array $body): array
    {
        throw new \LogicException('Not implemented.');
    }

    public function handleDigestAuth(string $url, string $path, HueClient $connectionClient, ?array $body = null): ?array
    {
        try {
            $this->rawRequest('POST', $url, null, ['body' => $body]);

            return null;
        } catch (ClientException $exception) {
            $headers = $exception->getResponse()->getHeaders(false);

            if (!isset($headers['www-authenticate'][0])) {
                throw new PhillipsHueException('No www-authenticate header found.', 0);
            }

            preg_match('/nonce="([a-zA-Z0-9]+)"/', $headers['www-authenticate'][0], $result);
            $nonce = $result[1];

            $hash1 = md5(
                $connectionClient->getClientId().
                ':oauth2_client@api.meethue.com:'.
                $connectionClient->getClientSecret()
            );
            $hash2 = md5('POST:'.$path);
            $response = md5($hash1.':'.$nonce.':'.$hash2);

            $authHeader = 'Digest username="'.$connectionClient->getClientId().'", ';
            $authHeader .= 'realm="oauth2_client@api.meethue.com", nonce="'.$nonce.'",';
            $authHeader .= 'uri="'.$path.'", response="'.$response.'"';

            return $this->rawRequest('POST', $url, null, [
                'body'    => $body,
                'headers' => [
                    'Authorization' => $authHeader,
                ],
            ])->toArray();
        }
    }
}
