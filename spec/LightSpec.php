<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use jkniest\HueIt\Light;
use PhpSpec\ObjectBehavior;
use jkniest\HueIt\DemoConstants;
use jkniest\HueIt\PhillipsHueClient;

class LightSpec extends ObjectBehavior
{
    public function let(PhillipsHueClient $client): void
    {
        $this->beConstructedWith(
            10,
            DemoConstants::LIGHT_DATA,
            $client
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Light::class);
    }

    public function it_can_return_the_id(): void
    {
        $this->getId()->shouldBe(10);
    }

    public function it_can_return_the_name(): void
    {
        $this->getName()->shouldBe('Example light 1');
    }

    public function it_can_return_the_on_state(): void
    {
        $this->isOn()->shouldBe(true);
    }

    public function it_can_set_the_on_state(PhillipsHueClient $client): void
    {
        $client->userRequest('PUT', 'lights/10/state', [
            'on' => false,
        ])->shouldBeCalledOnce();

        $this->setOn(false)->shouldBe($this);

        $this->isOn()->shouldBe(false);
    }

    public function it_can_turn_the_light_on(PhillipsHueClient $client): void
    {
        $client->userRequest('PUT', 'lights/10/state', [
            'on' => true,
        ])->shouldBeCalledOnce();

        $this->turnOn();
    }

    public function it_can_turn_the_light_off(PhillipsHueClient $client): void
    {
        $client->userRequest('PUT', 'lights/10/state', [
            'on' => false,
        ])->shouldBeCalledOnce();

        $this->turnOff();
    }

    public function it_can_return_the_brightness_in_percentage_and_raw_value(): void
    {
        $this->getBrightness()->shouldBe(61);
        $this->getBrightness(true)->shouldBe(156);
    }

    public function it_can_set_the_brightness_in_percentage_and_raw_value(PhillipsHueClient $client): void
    {
        $client->lightRequest($this, ['bri' => 203])->shouldBeCalledOnce();
        $this->setBrightness(80)->shouldBe($this);

        $client->lightRequest($this, ['bri' => 123])->shouldBeCalledOnce();
        $this->setBrightness(123, true)->shouldBe($this);
    }

    public function it_can_return_the_color_temperature_in_percentage_and_raw_value(): void
    {
        $this->getColorTemperature()->shouldBe(30);
        $this->getColorTemperature(true)->shouldBe(380);
    }

    public function it_can_return_the_saturation_in_percentage_and_raw_value(): void
    {
        $this->getSaturation()->shouldBe(30);
        $this->getSaturation(true)->shouldBe(77);
    }

    public function it_can_return_the_effect(): void
    {
        $this->getEffect()->shouldBe('none');
    }

    public function it_can_return_the_alert(): void
    {
        $this->getAlert()->shouldBe('lselect');
    }

    public function it_can_return_if_its_reachable(): void
    {
        $this->isReachable()->shouldBe(false);
    }

    public function it_can_return_the_colormode(): void
    {
        $this->getColorMode()->shouldBe('xy');
    }

    public function it_can_return_the_color_in_xy(): void
    {
        $this->getColorAsXY()->shouldBe([0.1234, 0.5678]);
    }

    public function it_can_return_the_xy_color_as_rgb(): void
    {
        $this->getColorAsRGB()->shouldBe([0, 247, 141]);
    }

    public function it_can_return_the_xy_color_as_hex(): void
    {
        $this->getColorAsHex()->shouldBe('#00f78d');
    }
}
