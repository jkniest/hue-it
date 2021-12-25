<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt\Exceptions;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Exceptions\PhillipsHueException;

class PhillipsHueExceptionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('Example error message', 304);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PhillipsHueException::class);
    }

    public function it_is_constructed_with_a_message_and_code(): void
    {
        $this->shouldBeAnInstanceOf(\Exception::class);
        $this->getMessage()->shouldBe('Example error message');
        $this->getCode()->shouldBe(304);
    }

    public function it_can_get_a_previous_exception(): void
    {
        $exception = new \LogicException('Something went wrong');
        $this->beConstructedWith('Message', 101, $exception);

        $this->getPrevious()->shouldBe($exception);
    }

    public function it_converts_non_integer_codes_to_integer(): void
    {
        $this->beConstructedWith('Example error message', 'invalid');

        $this->getCode()->shouldBe(-1);
    }
}
