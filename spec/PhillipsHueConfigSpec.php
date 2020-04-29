<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\DemoConstants;
use Illuminate\Support\Collection;
use jkniest\HueIt\WhitelistDevice;
use jkniest\HueIt\PhillipsHueConfig;

class PhillipsHueConfigSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(DemoConstants::CONFIG_DATA);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PhillipsHueConfig::class);
    }

    public function it_fetches_all_data_from_the_constructor(): void
    {
        $this->getName()->shouldBe('Bridge name');
        $this->getZigBeeChannel()->shouldBe(20);
        $this->getModelId()->shouldBe('BSB002');
        $this->getApiVersion()->shouldBe('1.37.0');
        $this->isLinkButtonPressed()->shouldBe(false);
        $this->getRawData()->shouldBe(DemoConstants::CONFIG_DATA);
    }

    public function it_returns_a_collection_with_all_whitelisted_devices(): void
    {
        $whitelist = $this->getWhitelist();
        $whitelist->shouldBeAnInstanceOf(Collection::class);
        $whitelist->shouldHaveCount(2);

        $whitelist[0]->shouldBeAnInstanceOf(WhitelistDevice::class);
        $whitelist[0]->getId()->shouldBe('d649c993-0bed-4865-9494-1b06aa412704');
        $whitelist[0]->getName()->shouldBe('Example device');

        $whitelist[1]->shouldBeAnInstanceOf(WhitelistDevice::class);
        $whitelist[1]->getId()->shouldBe('40506dae-dd6a-4699-9546-bca83d19de57');
        $whitelist[1]->getName()->shouldBe('Another');
    }
}
