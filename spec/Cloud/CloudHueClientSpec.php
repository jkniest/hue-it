<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt\Cloud;

use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Cloud\CloudHueClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Response\MockResponse;

class CloudHueClientSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CloudHueClient::class);
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

    public function it_can_make_requests_against_the_api_and_get_the_raw_response(): void
    {
        $callback = static function (string $method, string $url, array $options) {
            assert('POST' === $method);
            assert('https://api.meethue.com/resource-123' === $url);

            $body = json_decode($options['body'], true, 512, JSON_THROW_ON_ERROR);
            assert('value' === $body['example']);

            return new MockResponse(json_encode([
                'key'  => 'value',
                'nice' => 'done',
            ], JSON_THROW_ON_ERROR));
        };

        $client = new MockHttpClient($callback, 'https://api.meethue.com');
        $this->useClient($client);

        $result = $this->rawRequest('POST', 'resource-123', ['example' => 'value']);
        $result->shouldBeAnInstanceOf(ResponseInterface::class);
        $result->getStatusCode()->shouldBe(200);
        $result->toArray(false)->shouldBe(['key' => 'value', 'nice' => 'done']);
    }
}
