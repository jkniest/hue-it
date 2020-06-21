<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use jkniest\HueIt\Group;
use jkniest\HueIt\Light;
use PhpSpec\ObjectBehavior;
use jkniest\HueIt\DemoConstants;
use jkniest\HueIt\Local\LocalHueClient;

class GroupSpec extends ObjectBehavior
{
    public function let(LocalHueClient $client): void
    {
        $this->beConstructedWith(
            10,
            DemoConstants::GROUP_DATA,
            $client
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Group::class);
    }

    public function it_can_return_the_id(): void
    {
        $this->getId()->shouldBe(10);
    }

    public function it_can_return_the_name(): void
    {
        $this->getName()->shouldBe('Example group 1');
    }

    public function it_can_return_the_light_ids(): void
    {
        $result = $this->getLightIds()->toArray();

        $result->shouldBe([2, 5]);
    }

    public function it_can_return_all_lights(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'lights')
            ->shouldBeCalledOnce()
            ->willReturn([
                '1' => DemoConstants::LIGHT_DATA,
                '2' => DemoConstants::LIGHT_DATA,
                '5' => DemoConstants::LIGHT_DATA,
                '8' => DemoConstants::LIGHT_DATA,
            ]);

        $result = $this->getLights();
        $result->shouldHaveCount(2);
        $result->shouldHaveKey(2);
        $result->shouldHaveKey(5);

        $result->first()->shouldBeAnInstanceOf(Light::class);
    }

    public function it_can_return_the_type(): void
    {
        $this->getType()->shouldBe('Room');
    }

    public function it_can_return_if_all_lights_are_on(): void
    {
        $this->areAllOn()->shouldBe(false);
    }

    public function it_can_return_if_any_light_is_on(): void
    {
        $this->areAnyOn()->shouldBe(true);
    }

    public function it_can_return_the_class(): void
    {
        $this->getClass()->shouldBe('Bedroom');
    }
}
