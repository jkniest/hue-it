<?php

declare(strict_types=1);

namespace spec\jkniest\HueIt\Cloud;

use jkniest\HueIt\Light;
use PhpSpec\ObjectBehavior;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\CloudHueClient;
use Symfony\Component\HttpClient\MockHttpClient;
use jkniest\HueIt\Exceptions\PhillipsHueException;
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

    public function it_can_make_a_digest_authentication_call(
        ResponseInterface $finalResponse
    ): void {
        $count = 0;

        $responses = [
            new MockResponse([], [
                'response_headers' => [
                    'www-authenticate' => [
                        'Digest realm="oauth2_client@api.meethue.com", nonce="nonce123"',
                    ],
                ],
                'http_code' => 401,
            ]),
            new MockResponse(json_encode([
                'key'  => 'value',
                'nice' => 'done',
            ], JSON_THROW_ON_ERROR)),
        ];

        $callback = static function (string $method, string $url, array $options) use ($responses, &$count) {
            assert('POST' === $method);
            assert('https://api.meethue.com/oauth2/token?code=code-123&grant_type=authorization_code' === $url);

            if (1 !== $count) {
                return $responses[$count++];
            }

            $hash1 = md5('client-id-123:oauth2_client@api.meethue.com:client-secret-123');
            $hash2 = md5('POST:oauth2/token');
            $finalHash = md5($hash1.':nonce123:'.$hash2);

            $authHeader = 'Digest username="client-id-123", ';
            $authHeader .= 'realm="oauth2_client@api.meethue.com", nonce="nonce123",';
            $authHeader .= 'uri="oauth2/token", response="'.$finalHash.'"';

            assert($options['normalized_headers']['authorization'][0] === 'Authorization: '.$authHeader);

            return $responses[$count++];
        };

        $this->useClient(
            new MockHttpClient($callback, 'https://api.meethue.com')
        );

        $finalResponse->toArray()->willReturn([
            'access_token'  => 'access-123',
            'refresh_token' => 'refresh-123',
        ]);

        $client = new HueClient('client-id-123', 'client-secret-123');

        $response = $this->handleDigestAuth(
            'oauth2/token?code=code-123&grant_type=authorization_code',
            'oauth2/token',
            $client
        );

        $response->shouldBeArray();
        $response->shouldBe([
            'key'  => 'value',
            'nice' => 'done',
        ]);
    }

    public function it_returns_null_if_the_first_request_is_successfull_during_digest_auth(
        HueClient $connectionClient
    ): void {
        $client = new MockHttpClient([
            new MockResponse(),
        ], 'http://123.456/');

        $this->useClient($client);

        $this->handleDigestAuth('oauth/token', '/', $connectionClient)->shouldBe(null);
    }

    public function it_throws_an_exception_if_no_www_authenticate_header_is_present(
        HueClient $connectionClient
    ): void {
        $client = new MockHttpClient([
            new MockResponse('', ['http_code' => 401]),
        ], 'http://123.456/');

        $this->useClient($client);

        $this->shouldThrow(new PhillipsHueException('No www-authenticate header found.', 0))
            ->during('handleDigestAuth', ['oauth/token', '/', $connectionClient]);
    }

    public function it_can_make_a_normal_request(): void
    {
        $this->shouldThrow(\LogicException::class)
            ->during('request', ['GET', '/test']);
    }

    public function it_can_make_a_user_request(): void
    {
        $this->shouldThrow(\LogicException::class)
            ->during('userRequest', ['GET', '/test']);
    }

    public function it_can_make_a_light_request(Light $light): void
    {
        $this->shouldThrow(\LogicException::class)
            ->during('lightRequest', [$light, []]);
    }
}
