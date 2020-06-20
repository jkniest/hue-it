<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use jkniest\HueIt\Group;
use jkniest\HueIt\Light;
use PhpSpec\ObjectBehavior;
use jkniest\HueIt\PhillipsHue;
use jkniest\HueIt\DemoConstants;
use Illuminate\Support\Collection;
use jkniest\HueIt\PhillipsHueConfig;
use jkniest\HueIt\Local\LocalHueClient;

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
        $client->shouldBeAnInstanceOf(LocalHueClient::class);
        $client->getIp()->shouldBe('123.456.78.9');
    }

    public function it_can_override_the_client(LocalHueClient $client): void
    {
        $this->useClient($client)
            ->getClient()
            ->shouldBe($client);
    }

    public function it_authenticates_against_the_bridge(LocalHueClient $client): void
    {
        $client->request('POST', '', ['devicetype' => 'ExampleApp'])
            ->shouldBeCalledOnce()
            ->willReturn([['success' => ['username' => 'username-123']]]);

        $client->setUsername('username-123')->shouldBeCalledOnce();

        $this->useClient($client);

        $this->authenticate('ExampleApp')->shouldBe('username-123');
    }

    public function it_can_fetch_the_bridge_config(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'config')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::CONFIG_DATA);

        $this->useClient($client);

        $config = $this->getConfig();
        $config->shouldBeAnInstanceOf(PhillipsHueConfig::class);
        $config->getName()->shouldBe('Bridge name');
    }

    public function it_can_return_a_specific_light(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'lights/123')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::LIGHT_DATA);

        $this->useClient($client);

        $light = $this->getLight(123);
        $light->shouldBeAnInstanceOf(Light::class);
        $light->getId()->shouldBe(123);
        $light->getName()->shouldBe('Example light 1');
    }

    public function it_can_return_all_lights(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'lights')
            ->shouldBeCalledOnce()
            ->willReturn([
                '8'  => DemoConstants::LIGHT_DATA,
                '17' => DemoConstants::LIGHT_DATA,
            ]);

        $this->useClient($client);

        $lights = $this->getAllLights();
        $lights->shouldBeAnInstanceOf(Collection::class);
        $lights->shouldHaveCount(2);

        $lights[8]->shouldBeAnInstanceOf(Light::class);
        $lights[8]->getId()->shouldBe(8);

        $lights[17]->shouldBeAnInstanceOf(Light::class);
        $lights[17]->getId()->shouldBe(17);
    }

    public function it_can_return_a_specific_group(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'groups/123')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::GROUP_DATA);

        $this->useClient($client);

        $group = $this->getGroup(123);
        $group->getId()->shouldBe(123);
        $group->getName()->shouldBe('Example group 1');
    }

    public function it_can_return_all_groups(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'groups')
            ->shouldBeCalledOnce()
            ->willReturn([
                '8'  => DemoConstants::GROUP_DATA,
                '17' => DemoConstants::GROUP_DATA,
            ]);

        $this->useClient($client);

        $groups = $this->getAllGroups();
        $groups->shouldHaveCount(2);

        $groups[8]->shouldBeAnInstanceOf(Group::class);
        $groups[8]->getId()->shouldBe(8);

        $groups[17]->shouldBeAnInstanceOf(Group::class);
        $groups[17]->getId()->shouldBe(17);
    }
}
