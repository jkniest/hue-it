<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\PhillipsHue;

class PhillipsHueSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PhillipsHue::class);
    }
}
