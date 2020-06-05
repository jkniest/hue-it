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
