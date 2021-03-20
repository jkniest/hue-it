<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use Prophecy\Argument;
use jkniest\HueIt\Group;
use jkniest\HueIt\Light;
use PhpSpec\ObjectBehavior;
use jkniest\HueIt\DemoConstants;
use jkniest\HueIt\Local\LocalHueClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;

class GroupSpec extends ObjectBehavior
{
    public function let(LocalHueClient $client): void
    {
        $this->beConstructedWith(
            10,
            DemoConstants::GROUP_DATA,
            $client
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Group::class);
    }

    public function it_can_return_the_id(): void
    {
        $this->getId()->shouldBe(10);
    }

    public function it_can_return_the_name(): void
    {
        $this->getName()->shouldBe('Example group 1');
    }

    public function it_can_return_the_light_ids(): void
    {
        $result = $this->getLightIds()->toArray();

        $result->shouldBe([2, 5]);
    }

    public function it_can_return_all_lights(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'lights')
            ->shouldBeCalledOnce()
            ->willReturn([
                '1' => DemoConstants::LIGHT_DATA,
                '2' => DemoConstants::LIGHT_DATA,
                '5' => DemoConstants::LIGHT_DATA,
                '8' => DemoConstants::LIGHT_DATA,
            ]);

        $result = $this->getLights();
        $result->shouldHaveCount(2);
        $result->shouldHaveKey(2);
        $result->shouldHaveKey(5);

        $result->first()->shouldBeAnInstanceOf(Light::class);
    }

    public function it_can_return_the_type(): void
    {
        $this->getType()->shouldBe('Room');
    }

    public function it_can_return_if_all_lights_are_on(): void
    {
        $this->areAllOn()->shouldBe(false);
    }

    public function it_can_return_if_any_light_is_on(): void
    {
        $this->areAnyOn()->shouldBe(true);
    }

    public function it_can_return_the_class(): void
    {
        $this->getClass()->shouldBe('Bedroom');
    }

    public function it_can_set_the_on_state(LocalHueClient $client): void
    {
        $client->groupRequest($this, ['on' => false])->shouldBeCalledOnce();
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::GROUP_DATA);

        $this->setOn(false);
    }

    public function it_can_turn_the_group_on(LocalHueClient $client): void
    {
        $client->groupRequest($this, ['on' => true])->shouldBeCalledOnce();
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::GROUP_DATA);

        $this->turnOn()->shouldBe($this);
    }

    public function it_can_turn_the_group_off(LocalHueClient $client): void
    {
        $client->groupRequest($this, ['on' => false])->shouldBeCalledOnce();
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::GROUP_DATA);

        $this->turnOff()->shouldBe($this);
    }

    public function it_can_return_the_brightness_in_percentage_and_raw_value(): void
    {
        $this->getBrightness()->shouldBe(48);
        $this->getBrightness(true)->shouldBe(123);
    }

    public function it_can_set_the_brightness_in_percentage_and_raw_value(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledTimes(2)
            ->willReturn(DemoConstants::GROUP_DATA);

        $client->groupRequest($this, ['bri' => 204])->shouldBeCalledOnce();
        $this->setBrightness(80)->shouldBe($this);

        $client->groupRequest($this, ['bri' => 123])->shouldBeCalledOnce();
        $this->setBrightness(123, true)->shouldBe($this);
    }

    public function it_can_return_the_color_temperature_in_percentage_and_raw_value(): void
    {
        $this->getColorTemperature(true)->shouldBe(153);

        $this->shouldThrow(
            new PhillipsHueException(
                'Groups do currently not support color temperatures with percentage values.',
                -1
            )
        )->during('getColorTemperature', [false]);
    }

    public function it_can_set_the_color_temperature_in_percentage_and_raw_value(LocalHueClient $client): void
    {
        $this->shouldThrow(new PhillipsHueException(
            'Groups do currently not support color temperatures with percentage values.',
            -1
        ))->during('setColorTemperature', [80, false]);

        $client->groupRequest($this, ['ct' => 456])->shouldBeCalledOnce();
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::GROUP_DATA);

        $this->setColorTemperature(456, true)->shouldBe($this);
    }

    public function it_can_return_the_saturation_in_percentage_and_raw_value(): void
    {
        $this->getSaturation()->shouldBe(100);
        $this->getSaturation(true)->shouldBe(254);
    }

    public function it_can_set_the_saturation_in_percentage_and_raw_value(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledTimes(2)
            ->willReturn(DemoConstants::GROUP_DATA);

        $client->groupRequest($this, ['sat' => 204])->shouldBeCalledOnce();
        $this->setSaturation(80)->shouldBe($this);

        $client->groupRequest($this, ['sat' => 123])->shouldBeCalledOnce();
        $this->setSaturation(123, true)->shouldBe($this);
    }

    public function it_can_return_the_effect(): void
    {
        $this->getEffect()->shouldBe('none');
    }

    public function it_can_set_the_effect(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::GROUP_DATA);

        $client->groupRequest($this, ['effect' => 'colorloop'])->shouldBeCalledOnce();

        $this->setEffect('colorloop')->shouldBe($this);
    }

    public function it_can_return_the_alert(): void
    {
        $this->getAlert()->shouldBe('lselect');
    }

    public function it_can_set_the_alert(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::GROUP_DATA);

        $client->groupRequest($this, ['alert' => 'none'])->shouldBeCalledOnce();

        $this->setAlert('none')->shouldBe($this);
    }

    public function it_can_return_the_colormode(): void
    {
        $this->getColorMode()->shouldBe('xy');
    }

    public function it_can_return_the_color_in_xy(): void
    {
        $this->getColorAsXY()->shouldBe([0.2066, 0.6725]);
    }

    public function it_can_set_the_color_as_xy(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::GROUP_DATA);

        $client->groupRequest($this, ['xy' => [0.123, 0.456]])->shouldBeCalledOnce();

        $this->setColorAsXY(0.123, 0.456)->shouldBe($this);
    }

    public function it_can_return_the_xy_color_as_rgb(): void
    {
        $this->getColorAsRGB()->shouldBe([64, 217, 54]);
    }

    /** @noinspection PhpParamsInspection */
    public function it_can_set_the_color_as_rgb(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::GROUP_DATA);

        $client->groupRequest($this, Argument::that(static function (array $xy): bool {
            $xy = $xy['xy'];
            assert($xy[0] - 0.15126647230483 <= 0.001);
            assert($xy[1] - 0.12830512540722 <= 0.001);

            return true;
        }))->shouldBeCalledOnce();

        $this->setColorAsRGB(0, 100, 200)->shouldBe($this);
    }

    public function it_can_return_the_xy_color_as_hex(): void
    {
        $this->getColorAsHex()->shouldBe('#40d936');
    }

    /** @noinspection PhpParamsInspection */
    public function it_can_set_the_color_as_hex(LocalHueClient $client): void
    {
        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledOnce()
            ->willReturn(DemoConstants::GROUP_DATA);

        $client->groupRequest($this, Argument::that(static function (array $xy): bool {
            $xy = $xy['xy'];
            assert($xy[0] - 0.15126647230483 <= 0.001);
            assert($xy[1] - 0.12830512540722 <= 0.001);

            return true;
        }))->shouldBeCalledOnce();

        $this->setColorAsHex('0064c8')->shouldBe($this);
    }

    public function it_can_refresh_the_group(LocalHueClient $client): void
    {
        $this->getName()->shouldBe('Example group 1');
        //$this->isOn()->shouldBe(true);

        $newData = DemoConstants::GROUP_DATA;
        $newData['name'] = 'Changed name';
        $newData['action']['on'] = false;

        $client->userRequest('GET', 'groups/10')
            ->shouldBeCalledOnce()
            ->willReturn($newData);

        $this->refresh()->shouldBe($this);

        $this->getName()->shouldBe('Changed name');
        //$this->isOn()->shouldBe(false);
    }
}
