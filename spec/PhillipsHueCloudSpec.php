<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use Prophecy\Argument;
use jkniest\HueIt\Light;
use PhpSpec\ObjectBehavior;
use jkniest\HueIt\DemoConstants;
use Illuminate\Support\Collection;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;
use jkniest\HueIt\Cloud\HueTokens;
use jkniest\HueIt\PhillipsHueCloud;
use jkniest\HueIt\PhillipsHueConfig;
use jkniest\HueIt\Cloud\CloudHueClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;

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

    public function it_can_return_and_set_the_tokens(CloudHueClient $client): void
    {
        $this->useClient($client);

        $this->getTokens()->shouldBe(null);

        $client->setAccessToken('access-123')->shouldBeCalledOnce();

        $this->useTokens('access-123', 'refresh-123')->shouldBe($this);

        $this->getTokens()->shouldBeAnInstanceOf(HueTokens::class);
        $this->getTokens()->getAccessToken()->shouldBe('access-123');
        $this->getTokens()->getRefreshToken()->shouldBe('refresh-123');
    }

    public function it_updates_the_access_token_when_a_new_client_is_given(
        CloudHueClient $client
    ): void {
        $this->useTokens('access-123', 'refresh-123');

        $client->setUsername(null);
        $client->setAccessToken('access-123')->shouldBeCalledOnce();

        $this->useClient($client);
    }

    public function it_can_return_and_set_the_username(CloudHueClient $client): void
    {
        $this->useClient($client);

        $client->setUsername('user-123')->shouldBeCalledOnce();

        $this->useUsername('user-123');

        $this->getUsername()->shouldBe('user-123');
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

        $client->setAccessToken('access-123')->shouldBeCalledOnce();

        $result = $this->authenticate('code-123');
        $result->getAccessToken()->shouldBe('access-123');
        $result->getRefreshToken()->shouldBe('refresh-123');

        $this->getTokens()->shouldBe($result);
    }

    public function it_can_create_a_new_username(CloudHueClient $client): void
    {
        $this->useClient($client);

        $client->authRequest('PUT', 'bridge/0/config', ['linkbutton' => true])
            ->shouldBeCalledOnce();

        $client->authRequest('POST', 'bridge', ['devicetype' => 'device-id-123'])
            ->shouldBeCalledOnce()
            ->willReturn([['success' => ['username' => 'new-user-123']]]);

        $client->setUsername('new-user-123')->shouldBeCalledOnce();

        $this->createUsername()->shouldBe('new-user-123');

        $this->getUsername()->shouldBe('new-user-123');
    }

    public function it_throws_an_exception_if_no_username_was_returned(CloudHueClient $client): void
    {
        $this->useClient($client);

        $client->authRequest('PUT', 'bridge/0/config', ['linkbutton' => true]);

        $client->authRequest('POST', 'bridge', ['devicetype' => 'device-id-123'])
            ->willReturn([['success' => ['non-username' => 'ok']]]);

        $this->shouldThrow(new PhillipsHueException('No username returned.', -1))
            ->during('createUsername');
    }

    public function it_can_fetch_the_bridge_config(CloudHueClient $client): void
    {
        $client->userRequest('GET', 'config')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::CONFIG_DATA);

        $client->setUsername(null);
        $this->useClient($client);

        $config = $this->getConfig();
        $config->shouldBeAnInstanceOf(PhillipsHueConfig::class);
        $config->getName()->shouldBe('Bridge name');
    }

    public function it_can_return_a_specific_light(CloudHueClient $client): void
    {
        $client->userRequest('GET', 'lights/123')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::LIGHT_DATA);

        $client->setUsername(null);
        $this->useClient($client);

        $light = $this->getLight(123);
        $light->shouldBeAnInstanceOf(Light::class);
        $light->getId()->shouldBe(123);
        $light->getName()->shouldBe('Example light 1');
    }

    public function it_can_return_all_lights(CloudHueClient $client): void
    {
        $client->userRequest('GET', 'lights')
            ->shouldBeCalledOnce()
            ->willReturn([
                '8'  => DemoConstants::LIGHT_DATA,
                '17' => DemoConstants::LIGHT_DATA,
            ]);

        $client->setUsername(null);
        $this->useClient($client);

        $lights = $this->getAllLights();
        $lights->shouldBeAnInstanceOf(Collection::class);
        $lights->shouldHaveCount(2);

        $lights[8]->shouldBeAnInstanceOf(Light::class);
        $lights[8]->getId()->shouldBe(8);

        $lights[17]->shouldBeAnInstanceOf(Light::class);
        $lights[17]->getId()->shouldBe(17);
    }
}
