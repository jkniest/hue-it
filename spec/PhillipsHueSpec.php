<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use jkniest\HueIt\Group;
use jkniest\HueIt\Light;
use jkniest\HueIt\Model\Config;
use PhpSpec\ObjectBehavior;
use jkniest\HueIt\PhillipsHue;
use jkniest\HueIt\DemoConstants;
use Illuminate\Support\Collection;
use jkniest\HueIt\PhillipsHueConfig;
use jkniest\HueIt\Local\LocalHueClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

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

    public function it_can_be_constructed_with_the_application_key(): void
    {
        $this->beConstructedWith('123.456.78.9', 'app-key-123');

        $this->getApplicationKey()->shouldBe('app-key-123');
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
        $client->v1Request('POST', '', ['devicetype' => 'ExampleApp'])
            ->shouldBeCalledOnce()
            ->willReturn([['success' => ['username' => 'application-key-123']]]);

        $client->setApplicationKey('application-key-123')->shouldBeCalledOnce();

        $this->useClient($client);

        $this->authenticate('ExampleApp')->shouldBe('application-key-123');
    }

    public function it_can_fetch_the_bridge_config(LocalHueClient $client): void
    {
        $client->v1UserRequest('GET', 'config')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::CONFIG_DATA);

        $this->useClient($client);

        $config = $this->getConfig();
        $config->shouldBeAnInstanceOf(Config::class);
        $config->getName()->shouldBe('Bridge name');
    }

    public function it_can_return_a_specific_light(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'resource/light/id-123')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::LIGHT_DATA);

        $this->useClient($client);

        $light = $this->getLight('id-123');
        $light->shouldBeAnInstanceOf(\jkniest\HueIt\Model\Light::class);
        $light->getId()->shouldBe('id-123');
        $light->getMetaData()->getName()->shouldBe('Example light 1');
    }

    public function it_can_return_all_lights(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'resource/light')
            ->shouldBeCalledOnce()
            ->willReturn(['data' => [
                DemoConstants::LIGHT_DATA,
                array_merge_recursive(
                    DemoConstants::LIGHT_DATA,
                    ['data' => [0 => ['id-456']]]
                )
            ]]);

        $this->useClient($client);

        $lights = $this->getAllLights();
        $lights->shouldBeAnInstanceOf(Collection::class);
        $lights->shouldHaveCount(2);

        $lights['id-123']->shouldBeAnInstanceOf(\jkniest\HueIt\Model\Light::class);
        $lights['id-123']->getId()->shouldBe('id-123');

        $lights['id-456']->shouldBeAnInstanceOf(\jkniest\HueIt\Model\Light::class);
        $lights['id-456']->getId()->shouldBe('id-456');
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
                '8' => DemoConstants::GROUP_DATA,
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
