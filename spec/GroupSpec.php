<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use jkniest\HueIt\Group;
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
}
