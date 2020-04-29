<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\PhillipsHue;
use Symfony\Component\HttpClient\MockHttpClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Response\MockResponse;

class PhillipsHueSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('123.456.78.9');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PhillipsHue::class);
    }

    public function it_can_return_the_given_ip_address(): void
    {
        $this->getIp()->shouldBe('123.456.78.9');
    }

    public function it_can_be_constructed_with_the_username(): void
    {
        $this->beConstructedWith('123.456.78.9', 'username-123');

        $this->getUsername()->shouldBe('username-123');
    }

    public function it_can_return_the_used_client(): void
    {
        $this->getClient()->shouldBeAnInstanceOf(HttpClientInterface::class);
    }

    public function it_can_override_the_client(): void
    {
        $client = new MockHttpClient();

        $this->useClient($client)
            ->getClient()
            ->shouldBe($client);
    }

    public function it_authenticates_against_the_bridge(): void
    {
        $callback = static function (string $method, string $url, array $options) {
            assert('POST' === $method);
            assert('http://123.456.78.9/api' === $url);

            $body = json_decode($options['body'], true, 512, JSON_THROW_ON_ERROR);
            assert('ExampleApp' === $body['devicetype']);

            return new MockResponse(json_encode([
                [
                    'success' => [
                        'username' => 'username-123',
                    ],
                ],
            ], JSON_THROW_ON_ERROR));
        };

        $client = new MockHttpClient($callback, 'http://123.456.78.9');

        $this->useClient($client);

        $this->authenticate('ExampleApp')->shouldBe('username-123');
        $this->getUsername()->shouldBe('username-123');
    }

    public function it_caches_the_http_client_exceptions_and_mocks_them_in_phillips_hue_exceptions(): void
    {
        $client = new MockHttpClient([
            new MockResponse('{"ok": true}', ['error' => 'Not found']),
            new MockResponse('{"ok": true}', ['http_code' => 404]),
        ], 'http://123.456.78.9');

        $this->useClient($client);

        $this->shouldThrow(new PhillipsHueException('Not found', 0))
            ->during('authenticate', ['ExampleApp']);

        $this->shouldThrow(new PhillipsHueException('HTTP 404 returned for "http://123.456.78.9/api".', 404))
            ->during('authenticate', ['device-123']);
    }

    public function it_handles_errors_during_authentication(): void
    {
        $client = new MockHttpClient([
            new MockResponse(json_encode([['error' => [
                'type'        => 101,
                'address'     => '',
                'description' => 'link button not pressed',
            ]]], JSON_THROW_ON_ERROR)),
        ], 'http://123.456.78.9');

        $this->useClient($client);

        $this->shouldThrow(new PhillipsHueException('link button not pressed', 101))
            ->during('authenticate', ['ExampleApp']);
    }
}
