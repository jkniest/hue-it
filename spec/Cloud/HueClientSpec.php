<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt\Cloud;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Cloud\HueClient;

class HueClientSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('id-123', 'secret-123');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(HueClient::class);
    }

    public function it_can_return_the_client_id(): void
    {
        $this->getClientId()->shouldBe('id-123');
    }

    public function it_can_return_the_client_secret(): void
    {
        $this->getClientSecret()->shouldBe('secret-123');
    }
}
