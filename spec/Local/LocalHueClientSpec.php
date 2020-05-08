<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt\Local;

use jkniest\HueIt\Light;
use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Local\LocalHueClient;
use Symfony\Component\HttpClient\MockHttpClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Response\MockResponse;

class LocalHueClientSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('123.456.78.9');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LocalHueClient::class);
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

    public function it_can_make_requests_against_the_bridge_and_get_the_raw_response(): void
    {
        $callback = static function (string $method, string $url, array $options) {
            assert('POST' === $method);
            assert('http://123.456.78.9/api/resource-123' === $url);

            $body = json_decode($options['body'], true, 512, JSON_THROW_ON_ERROR);
            assert('value' === $body['example']);

            return new MockResponse(json_encode([
                'key'  => 'value',
                'nice' => 'done',
            ], JSON_THROW_ON_ERROR));
        };

        $client = new MockHttpClient($callback, 'http://123.456.78.9');
        $this->useClient($client);

        $result = $this->rawRequest('POST', 'resource-123', ['example' => 'value']);
        $result->shouldBeAnInstanceOf(ResponseInterface::class);
        $result->getStatusCode()->shouldBe(200);
        $result->toArray(false)->shouldBe(['key' => 'value', 'nice' => 'done']);
    }

    public function it_can_make_requests_against_the_bridge(): void
    {
        $callback = static function (string $method, string $url, array $options) {
            assert('POST' === $method);
            assert('http://123.456.78.9/api/resource-123' === $url);

            $body = json_decode($options['body'], true, 512, JSON_THROW_ON_ERROR);
            assert('value' === $body['example']);

            return new MockResponse(json_encode([
                'key'  => 'value',
                'nice' => 'done',
            ], JSON_THROW_ON_ERROR));
        };

        $client = new MockHttpClient($callback, 'http://123.456.78.9');
        $this->useClient($client);

        $this->request('POST', 'resource-123', ['example' => 'value'])->shouldBe([
            'key'  => 'value',
            'nice' => 'done',
        ]);
    }

    public function it_catches_the_http_client_exceptions_and_mocks_them_in_phillips_hue_exceptions(): void
    {
        $client = new MockHttpClient([
            new MockResponse('{"ok": true}', ['error' => 'Not found']),
            new MockResponse('{"ok": true}', ['http_code' => 404]),
        ], 'http://123.456.78.9');

        $this->useClient($client);

        $this->shouldThrow(new PhillipsHueException('Not found', 0))
            ->during('request', ['GET', 'example']);

        $this->shouldThrow(new PhillipsHueException('HTTP 404 returned for "http://123.456.78.9/api/example".', 404))
            ->during('request', ['GET', 'example']);
    }

    public function it_handles_errors(): void
    {
        $client = new MockHttpClient([
            new MockResponse(json_encode([['error' => [
                'type'        => 123,
                'address'     => '',
                'description' => 'Something went wrong.',
            ]]], JSON_THROW_ON_ERROR)),
        ], 'http://123.456.78.9');

        $this->useClient($client);

        $this->shouldThrow(new PhillipsHueException('Something went wrong.', 123))
            ->during('request', ['GET', 'example']);
    }

    public function it_can_update_the_username(): void
    {
        $this->setUsername('another username');

        $this->getUsername()->shouldBe('another username');
    }

    public function it_can_make_username_specific_requests(): void
    {
        $this->setUsername('username-123');

        $callback = static function (string $method, string $url, array $options) {
            assert('POST' === $method);
            assert('http://123.456.78.9/api/username-123/resource-123' === $url);

            $body = json_decode($options['body'], true, 512, JSON_THROW_ON_ERROR);
            assert('value' === $body['example']);

            return new MockResponse(json_encode([
                'key'  => 'value',
                'nice' => 'done',
            ], JSON_THROW_ON_ERROR));
        };

        $client = new MockHttpClient($callback, 'http://123.456.78.9');
        $this->useClient($client);

        $this->userRequest('POST', 'resource-123', ['example' => 'value'])->shouldBe([
            'key'  => 'value',
            'nice' => 'done',
        ]);
    }

    public function it_can_make_light_specific_requests(Light $light): void
    {
        $this->setUsername('username-123');

        $callback = static function (string $method, string $url, array $options) {
            assert('PUT' === $method);
            assert('http://123.456.78.9/api/username-123/lights/123/state' === $url);

            $body = json_decode($options['body'], true, 512, JSON_THROW_ON_ERROR);
            assert(true === $body['on']);

            return new MockResponse(json_encode([
                'key'  => 'value',
                'nice' => 'done',
            ], JSON_THROW_ON_ERROR));
        };

        $client = new MockHttpClient($callback, 'http://123.456.78.9');
        $this->useClient($client);

        $light->getId()->willReturn(123);

        $this->lightRequest($light, ['on' => true])->shouldBe([
            'key'  => 'value',
            'nice' => 'done',
        ]);
    }
}
