<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\PhillipsHue;
use jkniest\HueIt\PhillipsHueClient;
use jkniest\HueIt\PhillipsHueConfig;

class PhillipsHueSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('123.456.78.9');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PhillipsHue::class);
    }

    public function it_can_return_the_given_ip_address(): void
    {
        $this->getIp()->shouldBe('123.456.78.9');
    }

    public function it_can_be_constructed_with_the_username(): void
    {
        $this->beConstructedWith('123.456.78.9', 'username-123');

        $this->getUsername()->shouldBe('username-123');
    }

    public function it_can_return_the_used_client(): void
    {
        $client = $this->getClient();
        $client->shouldBeAnInstanceOf(PhillipsHueClient::class);
        $client->getIp()->shouldBe('123.456.78.9');
    }

    public function it_can_override_the_client(PhillipsHueClient $client): void
    {
        $this->useClient($client)
            ->getClient()
            ->shouldBe($client);
    }

    public function it_authenticates_against_the_bridge(PhillipsHueClient $client): void
    {
        $client->request('POST', '', ['devicetype' => 'ExampleApp'])
            ->shouldBeCalledOnce()
            ->willReturn([['success' => ['username' => 'username-123']]]);

        $client->setUsername('username-123')->shouldBeCalledOnce();

        $this->useClient($client);

        $this->authenticate('ExampleApp')->shouldBe('username-123');
    }

    public function it_can_fetch_the_bridge_config(PhillipsHueClient $client): void
    {
        $client->userRequest('GET', 'config')
            ->shouldBeCalledOnce()
            ->willReturn(PhillipsHueConfigSpec::RAW_DATA);

        $this->useClient($client);

        $config = $this->getConfig();
        $config->shouldBeAnInstanceOf(PhillipsHueConfig::class);
        $config->getName()->shouldBe('Bridge name');
    }
}
