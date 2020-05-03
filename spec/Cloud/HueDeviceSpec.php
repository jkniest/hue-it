<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt\Cloud;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Cloud\HueDevice;

class HueDeviceSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('id-123', 'name-123');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(HueDevice::class);
    }

    public function it_can_return_the_id(): void
    {
        $this->getId()->shouldBe('id-123');
    }

    public function it_can_return_the_name(): void
    {
        $this->getName()->shouldBe('name-123');
    }
}
