<?php

declare(strict_types=1);

namespace jkniest\HueIt\Cloud;

use jkniest\HueIt\PhillipsHueCloud;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements  Arrayable<string, string>
 */
class HueTokens implements Arrayable
{
    public function __construct(
        private string $accessToken,
        private string $refreshToken,
        private PhillipsHueCloud $cloud,
    ) {}

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function refresh(): self
    {
        $newTokens = $this->cloud->getClient()->handleDigestAuth(
            'oauth2/refresh?grant_type=refresh_token',
            '/oauth2/refresh',
            $this->cloud->getConnectionClient(),
            ['refresh_token' => $this->refreshToken],
        );

        $this->accessToken = $newTokens['access_token'] ?? '';
        $this->refreshToken = $newTokens['refresh_token'] ?? '';

        return $this;
    }

    public function toArray(): array
    {
        return [
            'access_token'  => $this->accessToken,
            'refresh_token' => $this->refreshToken,
        ];
    }
}
