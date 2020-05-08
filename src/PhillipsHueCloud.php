<?php

declare(strict_types=1);

namespace jkniest\HueIt;

use Illuminate\Support\Collection;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;
use jkniest\HueIt\Cloud\HueTokens;
use jkniest\HueIt\Cloud\CloudHueClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;
use Symfony\Component\HttpClient\Exception\ClientException;

class PhillipsHueCloud implements PhillipsHueGateway
{
    private CloudHueClient $client;

    private HueClient $connectionClient;

    private HueDevice $device;

    private string $appId;

    private ?HueTokens $tokens = null;

    public function __construct(HueClient $connectionClient, HueDevice $device, string $appId)
    {
        $this->connectionClient = $connectionClient;
        $this->device = $device;
        $this->appId = $appId;
        $this->client = new CloudHueClient();
    }

    public function getClient(): CloudHueClient
    {
        return $this->client;
    }

    public function useClient(CloudHueClient $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getOAuthUrl(string $state): string
    {
        $url = 'https://api.meethue.com/oauth2/auth'.
            '?clientid='.$this->connectionClient->getClientId().
            '&appid='.$this->appId.
            '&deviceid='.$this->device->getId().
            '&state='.$state.
            '&response_type=code';

        if (null !== $this->device->getName()) {
            $url .= '&devicename='.$this->device->getName();
        }

        return $url;
    }

    public function authenticate(string $code): HueTokens
    {
        try {
            $this->client->rawRequest(
                'POST',
                "oauth2/token?code={$code}&grant_type=authorization_code"
            );

            return new HueTokens('', '', $this->client);
        } catch (ClientException $exception) {
            $headers = $exception->getResponse()->getHeaders(false);

            if (!isset($headers['www-authenticate'][0])) {
                throw new PhillipsHueException('No www-authenticate header found.', 0);
            }

            preg_match('/nonce="([a-zA-Z0-9]+)"/', $headers['www-authenticate'][0], $result);
            $nonce = $result[1];

            $hash1 = md5(
                $this->connectionClient->getClientId().
                ':oauth2_client@api.meethue.com:'.
                $this->connectionClient->getClientSecret()
            );
            $hash2 = md5('POST:/oauth2/token');
            $response = md5($hash1.':'.$nonce.':'.$hash2);

            $authHeader = 'Digest username="'.$this->connectionClient->getClientId().'", ';
            $authHeader .= 'realm="oauth2_client@api.meethue.com", nonce="'.$nonce.'",';
            $authHeader .= 'uri="/oauth2/token", response="'.$response.'"';

            $tokens = $this->client->rawRequest(
                'POST',
                "oauth2/token?code={$code}&grant_type=authorization_code",
                null,
                [
                    'headers' => [
                        'Authorization' => $authHeader,
                    ],
                ]
            )->toArray();

            return $this->tokens = new HueTokens(
                $tokens['access_token'],
                $tokens['refresh_token'],
                $this->client
            );
        }
    }

    public function getConfig(): PhillipsHueConfig
    {
        throw new \LogicException('No');
    }

    public function getLight(int $id): Light
    {
        throw new \LogicException('No');
    }

    public function getAllLights(): Collection
    {
        throw new \LogicException('No');
    }
}
