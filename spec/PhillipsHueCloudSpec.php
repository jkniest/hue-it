<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;
use jkniest\HueIt\PhillipsHueCloud;

class PhillipsHueCloudSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(
            new HueClient('client-id-123', 'client-secret-123'),
            new HueDevice('device-id-123', 'device-name-123'),
            'app-id-123'
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PhillipsHueCloud::class);
    }

    public function it_can_generate_an_oauth_url(): void
    {
        $this->getOAuthUrl('state-123')->shouldBe(
            'https://api.meethue.com/oauth2/auth?'
            .'clientid=client-id-123&appid=app-id-123&'
            .'deviceid=device-id-123&state=state-123'
            .'&response_type=code&devicename=device-name-123'
        );
    }

    public function it_can_generate_an_oauth_url_without_device_name(): void
    {
        $this->beConstructedWith(
            new HueClient('client-id-123', 'client-secret-123'),
            new HueDevice('device-id-123'),
            'app-id-123'
        );

        $this->getOAuthUrl('state-123')->shouldBe(
            'https://api.meethue.com/oauth2/auth?'
            .'clientid=client-id-123&appid=app-id-123&'
            .'deviceid=device-id-123&state=state-123'
            .'&response_type=code'
        );
    }
}
