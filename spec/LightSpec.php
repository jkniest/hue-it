<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use jkniest\HueIt\Light;
use PhpSpec\ObjectBehavior;

class LightSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(\jkniest\HueIt\DemoConstants::LIGHT_DATA);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Light::class);
    }

    public function it_can_return_the_name(): void
    {
        $this->getName()->shouldBe('Example light 1');
    }
}
