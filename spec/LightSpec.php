<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use Prophecy\Argument;
use jkniest\HueIt\Light;
use PhpSpec\ObjectBehavior;
use jkniest\HueIt\DemoConstants;
use jkniest\HueIt\Local\LocalHueClient;

class LightSpec extends ObjectBehavior
{
    public function let(LocalHueClient $client): void
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

    public function it_can_set_the_on_state(LocalHueClient $client): void
    {
        $client->lightRequest($this, ['on' => false])->shouldBeCalledOnce();

        $this->setOn(false)->shouldBe($this);

        $this->isOn()->shouldBe(false);
    }

    public function it_can_turn_the_light_on(LocalHueClient $client): void
    {
        $client->lightRequest($this, ['on' => true])->shouldBeCalledOnce();

        $this->turnOn()->shouldBe($this);
    }

    public function it_can_turn_the_light_off(LocalHueClient $client): void
    {
        $client->lightRequest($this, ['on' => false])->shouldBeCalledOnce();

        $this->turnOff()->shouldBe($this);
    }

    public function it_can_return_the_brightness_in_percentage_and_raw_value(): void
    {
        $this->getBrightness()->shouldBe(61);
        $this->getBrightness(true)->shouldBe(156);
    }

    public function it_can_set_the_brightness_in_percentage_and_raw_value(LocalHueClient $client): void
    {
        $client->lightRequest($this, ['bri' => 204])->shouldBeCalledOnce();
        $this->setBrightness(80)->shouldBe($this);
        $this->getBrightness(true)->shouldBe(204);

        $client->lightRequest($this, ['bri' => 123])->shouldBeCalledOnce();
        $this->setBrightness(123, true)->shouldBe($this);
        $this->getBrightness(true)->shouldBe(123);
    }

    public function it_can_return_the_color_temperature_in_percentage_and_raw_value(): void
    {
        $this->getColorTemperature()->shouldBe(30);
        $this->getColorTemperature(true)->shouldBe(380);
    }

    public function it_can_set_the_color_temperature_in_percentage_and_raw_value(LocalHueClient $client): void
    {
        $client->lightRequest($this, ['ct' => 680])->shouldBeCalledOnce();
        $this->setColorTemperature(80)->shouldBe($this);
        $this->getColorTemperature(true)->shouldBe(680);

        $client->lightRequest($this, ['ct' => 456])->shouldBeCalledOnce();
        $this->setColorTemperature(456, true)->shouldBe($this);
        $this->getColorTemperature(true)->shouldBe(456);
    }

    public function it_can_return_the_saturation_in_percentage_and_raw_value(): void
    {
        $this->getSaturation()->shouldBe(30);
        $this->getSaturation(true)->shouldBe(77);
    }

    public function it_can_set_the_saturation_in_percentage_and_raw_value(LocalHueClient $client): void
    {
        $client->lightRequest($this, ['sat' => 204])->shouldBeCalledOnce();
        $this->setSaturation(80)->shouldBe($this);
        $this->getSaturation(true)->shouldBe(204);

        $client->lightRequest($this, ['sat' => 123])->shouldBeCalledOnce();
        $this->setSaturation(123, true)->shouldBe($this);
        $this->getSaturation(true)->shouldBe(123);
    }

    public function it_can_return_the_effect(): void
    {
        $this->getEffect()->shouldBe('none');
    }

    public function it_can_set_the_effect(LocalHueClient $client): void
    {
        $client->lightRequest($this, ['effect' => 'colorloop'])->shouldBeCalledOnce();

        $this->setEffect('colorloop')->shouldBe($this);

        $this->getEffect()->shouldBe('colorloop');
    }

    public function it_can_return_the_alert(): void
    {
        $this->getAlert()->shouldBe('lselect');
    }

    public function it_can_set_the_alert(LocalHueClient $client): void
    {
        $client->lightRequest($this, ['alert' => 'none'])->shouldBeCalledOnce();

        $this->setAlert('none')->shouldBe($this);

        $this->getAlert()->shouldBe('none');
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

    public function it_can_set_the_color_as_xy(LocalHueClient $client): void
    {
        $client->lightRequest($this, ['xy' => [0.123, 0.456]])->shouldBeCalledOnce();

        $this->setColorAsXY(0.123, 0.456)->shouldBe($this);
        $this->getColorAsXY()->shouldBe([0.123, 0.456]);
    }

    public function it_can_return_the_xy_color_as_rgb(): void
    {
        $this->getColorAsRGB()->shouldBe([0, 247, 141]);
    }

    /** @noinspection PhpParamsInspection */
    public function it_can_set_the_color_as_rgb(LocalHueClient $client): void
    {
        $client->lightRequest($this, Argument::that(static function (array $xy): bool {
            $xy = $xy['xy'];
            assert($xy[0] - 0.15126647230483 <= 0.001);
            assert($xy[1] - 0.12830512540722 <= 0.001);

            return true;
        }))->shouldBeCalledOnce();

        $this->setColorAsRGB(0, 100, 200)->shouldBe($this);

        $colors = $this->getColorAsXY();
        $colors[0]->shouldBeApproximately(0.151, 3);
        $colors[1]->shouldBeApproximately(0.128, 3);
    }

    public function it_can_return_the_xy_color_as_hex(): void
    {
        $this->getColorAsHex()->shouldBe('#00f78d');
    }

    /** @noinspection PhpParamsInspection */
    public function it_can_set_the_color_as_hex(LocalHueClient $client): void
    {
        $client->lightRequest($this, Argument::that(static function (array $xy): bool {
            $xy = $xy['xy'];
            assert($xy[0] - 0.15126647230483 <= 0.001);
            assert($xy[1] - 0.12830512540722 <= 0.001);

            return true;
        }))->shouldBeCalledOnce();

        $this->setColorAsHex('0064c8')->shouldBe($this);

        $colors = $this->getColorAsXY();
        $colors[0]->shouldBeApproximately(0.151, 3);
        $colors[1]->shouldBeApproximately(0.128, 3);
    }

    public function it_can_refresh_the_light(LocalHueClient $client): void
    {
        $this->getName()->shouldBe('Example light 1');
        $this->isOn()->shouldBe(true);

        $newData = DemoConstants::LIGHT_DATA;
        $newData['name'] = 'Changed name';
        $newData['state']['on'] = false;

        $client->userRequest('GET', 'lights/10')
            ->shouldBeCalledOnce()
            ->willReturn($newData);

        $this->refresh()->shouldBe($this);

        $this->getName()->shouldBe('Changed name');
        $this->isOn()->shouldBe(false);
    }
}
