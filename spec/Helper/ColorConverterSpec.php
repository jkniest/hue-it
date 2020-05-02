<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt\Helper;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Helper\ColorConverter;

class ColorConverterSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ColorConverter::class);
    }

    public function it_can_covert_colors_from_xy_to_rgb(): void
    {
        $this::fromXYToRGB(0.1234, 0.5678, 61)->shouldBe([
            0,
            247,
            141,
        ]);
    }

    public function it_can_covert_colors_from_xy_to_hex(): void
    {
        $this::fromXYToHex(0.1234, 0.5678, 61)->shouldBe('#00f78d');
    }

    public function it_can_convert_rgb_colors_to_xy(): void
    {
        $result = $this::fromRGBToXY(0, 100, 200);
        $result[0]->shouldBeApproximately(0.151, 3);
        $result[1]->shouldBeApproximately(0.128, 3);
    }
}
