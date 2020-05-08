<?php

declare(strict_types=1);

namespace jkniest\HueIt\Cloud;

use jkniest\HueIt\PhillipsHueClient;

class HueTokens
{
    public string $accessToken;

    public string $refreshToken;

    private PhillipsHueClient $client;

    public function __construct(string $accessToken, string $refreshToken, PhillipsHueClient $client)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->client = $client;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
