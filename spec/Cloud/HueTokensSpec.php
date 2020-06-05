<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt\Cloud;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueTokens;
use jkniest\HueIt\PhillipsHueCloud;
use jkniest\HueIt\Cloud\CloudHueClient;

class HueTokensSpec extends ObjectBehavior
{
    public function let(PhillipsHueCloud $cloud): void
    {
        $this->beConstructedWith('access-token-123', 'refresh-token-123', $cloud);
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

    public function it_can_return_an_array_with_both_tokens(): void
    {
        $this->toArray()->shouldBe([
            'access_token'  => 'access-token-123',
            'refresh_token' => 'refresh-token-123',
        ]);
    }

    public function it_can_refresh_the_access_token(
        PhillipsHueCloud $cloud,
        CloudHueClient $client,
        HueClient $connectionClient
    ): void {
        $cloud->getConnectionClient()->willReturn($connectionClient);
        $cloud->getClient()->willReturn($client);

        $client->handleDigestAuth(
            'oauth2/refresh?grant_type=refresh_token',
            '/oauth2/refresh',
            $connectionClient,
            ['refresh_token' => 'refresh-token-123']
        )->shouldBeCalledOnce()->willReturn([
            'access_token'  => 'new-access-123',
            'refresh_token' => 'new-refresh-123',
        ]);

        $this->refresh()->shouldBe($this);

        $this->getAccessToken()->shouldBe('new-access-123');
        $this->getRefreshToken()->shouldBe('new-refresh-123');
    }
}
