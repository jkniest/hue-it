<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt\Cloud;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Cloud\HueTokens;
use jkniest\HueIt\PhillipsHueClient;

class HueTokensSpec extends ObjectBehavior
{
    public function let(PhillipsHueClient $client): void
    {
        $this->beConstructedWith('access-token-123', 'refresh-token-123', $client);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(HueTokens::class);
    }

    public function it_can_return_the_access_and_refresh_tokens(): void
    {
        $this->getAccessToken()->shouldBe('access-token-123');
        $this->getRefreshToken()->shouldBe('refresh-token-123');
    }
}
