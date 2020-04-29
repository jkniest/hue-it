<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\WhitelistDevice;

class WhitelistDeviceSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('2b89fbb4-1d48-47af-8035-b89afd40e3e9', [
            'last use date' => '2018-10-17T01:06:14',
            'create date'   => '2017-12-28T17:34:32',
            'name'          => 'Example Device',
        ]);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(WhitelistDevice::class);
    }

    public function it_can_return_all_given_data_with_real_datetimes(): void
    {
        $this->getId()->shouldBe('2b89fbb4-1d48-47af-8035-b89afd40e3e9');
        $this->getName()->shouldBe('Example Device');

        $lastUseDate = $this->getLastUseDate();
        $lastUseDate->shouldBeAnInstanceOf(\DateTimeImmutable::class);
        $lastUseDate->getTimestamp()->shouldBe(1539738374);

        $createDate = $this->getCreateDate();
        $createDate->shouldBeAnInstanceOf(\DateTimeImmutable::class);
        $createDate->getTimestamp()->shouldBe(1514482472);
    }
}
