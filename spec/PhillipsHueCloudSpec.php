<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;
use jkniest\HueIt\Cloud\HueTokens;
use jkniest\HueIt\PhillipsHueCloud;
use jkniest\HueIt\Cloud\CloudHueClient;

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

    public function it_can_return_and_change_the_client(CloudHueClient $client): void
    {
        $this->useClient($client)->shouldBe($this);

        $this->getClient()->shouldBe($client);
    }

    public function it_can_return_the_connection_client(): void
    {
        $this->getConnectionClient()->getClientId()->shouldBe('client-id-123');
        $this->getConnectionClient()->getClientSecret()->shouldBe('client-secret-123');
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

    public function it_can_return_and_set_the_tokens(): void
    {
        $this->getTokens()->shouldBe(null);

        $this->useTokens('access-123', 'refresh-123')->shouldBe($this);

        $this->getTokens()->shouldBeAnInstanceOf(HueTokens::class);
        $this->getTokens()->getAccessToken()->shouldBe('access-123');
        $this->getTokens()->getRefreshToken()->shouldBe('refresh-123');
    }

    public function it_can_authenticate_with_a_given_code(CloudHueClient $client): void
    {
        $this->useClient($client);

        $client->handleDigestAuth(
            'oauth2/token?code=code-123&grant_type=authorization_code',
            '/oauth2/token',
            Argument::type(HueClient::class)
        )->shouldBeCalledOnce()->willReturn([
            'access_token'  => 'access-123',
            'refresh_token' => 'refresh-123',
        ]);

        $result = $this->authenticate('code-123');
        $result->getAccessToken()->shouldBe('access-123');
        $result->getRefreshToken()->shouldBe('refresh-123');

        $this->getTokens()->shouldBe($result);
    }

    public function it_can_return_the_config(): void
    {
        $this->shouldThrow(\LogicException::class)
            ->during('getConfig');
    }

    public function it_can_return_a_specific_light(): void
    {
        $this->shouldThrow(\LogicException::class)
            ->during('getLight', [3]);
    }

    public function it_can_return_all_lights(): void
    {
        $this->shouldThrow(\LogicException::class)
            ->during('getAllLights');
    }
}
