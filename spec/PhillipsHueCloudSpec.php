<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;
use jkniest\HueIt\Cloud\HueTokens;
use jkniest\HueIt\PhillipsHueCloud;
use jkniest\HueIt\Cloud\CloudHueClient;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\Exception\ClientException;

class PhillipsHueCloudSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(
            new HueClient('client-id-123', 'client-secret-123'),
            new HueDevice('device-id-123', 'device-name-123'),
            'app-id-123'
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PhillipsHueCloud::class);
    }

    public function it_can_generate_an_oauth_url(): void
    {
        $this->getOAuthUrl('state-123')->shouldBe(
            'https://api.meethue.com/oauth2/auth?'
            .'clientid=client-id-123&appid=app-id-123&'
            .'deviceid=device-id-123&state=state-123'
            .'&response_type=code&devicename=device-name-123'
        );
    }

    public function it_can_generate_an_oauth_url_without_device_name(): void
    {
        $this->beConstructedWith(
            new HueClient('client-id-123', 'client-secret-123'),
            new HueDevice('device-id-123'),
            'app-id-123'
        );

        $this->getOAuthUrl('state-123')->shouldBe(
            'https://api.meethue.com/oauth2/auth?'
            .'clientid=client-id-123&appid=app-id-123&'
            .'deviceid=device-id-123&state=state-123'
            .'&response_type=code'
        );
    }

    public function it_can_authenticate_with_the_code_and_client_credentials(
        CloudHueClient $client,
        ResponseInterface $finalResponse
    ): void {
        $this->useClient($client);

        $initialResponse = new MockResponse([], [
            'response_headers' => [
                'www-authenticate' => [
                    'Digest realm="oauth2_client@api.meethue.com", nonce="nonce123"',
                ],
            ],
        ]);

        $client->rawRequest('POST', 'oauth2/token?code=code-123&grant_type=authorization_code')
            ->shouldBeCalledOnce()
            ->willThrow(new ClientException($initialResponse));

        $hash1 = md5('client-id-123:oauth2_client@api.meethue.com:client-secret-123');
        $hash2 = md5('POST:/oauth2/token');
        $finalHash = md5($hash1.':nonce123:'.$hash2);

        $authHeader = 'Digest username="client-id-123", ';
        $authHeader .= 'realm="oauth2_client@api.meethue.com", nonce="nonce123",';
        $authHeader .= 'uri="/oauth2/token", response="'.$finalHash.'"';

        $finalResponse->toArray()->willReturn([
            'access_token'  => 'access-123',
            'refresh_token' => 'refresh-123',
        ]);

        $client->rawRequest(
            'POST',
            'oauth2/token?code=code-123&grant_type=authorization_code',
            null,
            [
                'headers' => [
                    'Authorization' => $authHeader,
                ],
            ]
        )->shouldBeCalledOnce()->willReturn($finalResponse);

        $tokens = $this->authenticate('code-123');
        $tokens->shouldBeAnInstanceOf(HueTokens::class);
        $tokens->getAccessToken()->shouldBe('access-123');
        $tokens->getRefreshToken()->shouldBe('refresh-123');
    }
}
