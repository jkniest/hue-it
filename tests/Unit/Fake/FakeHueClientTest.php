<?php

use jkniest\HueIt\Exceptions\NotAuthenticatedException;
use jkniest\HueIt\Fake\FakeHueClient;
use jkniest\HueIt\Fake\Model\FakeLight;

it('can return fake lights', function () {
    $client = new FakeHueClient();
    $client->authenticate(FakeHueClient::VALID_HOST, FakeHueClient::VALID_TOKEN);

    $client->setFakeLights([
        FakeLight::create()->id('id-123')->name('Test Light'),
        FakeLight::create()->id('id-456')->name('Test Light 2'),
    ]);

    $lights = $client->get('/resource/light');

    expect($lights)->toHaveKeys(['data'])
        ->and($lights['data'])->toBeArray()
        ->and($lights['data'])->toHaveCount(2)
        ->and($lights['data'][0])->toHaveKeys(['id', 'metadata.name'])
        ->and($lights['data'][0]['id'])->toBe('id-123')
        ->and($lights['data'][0]['metadata']['name'])->toBe('Test Light')
        ->and($lights['data'][1])->toHaveKeys(['id', 'metadata.name'])
        ->and($lights['data'][1]['id'])->toBe('id-456')
        ->and($lights['data'][1]['metadata']['name'])->toBe('Test Light 2');
});

it('returns an empty array if the endpoint is unknown', function (): void {
    $client = new FakeHueClient();
    $client->authenticate(FakeHueClient::VALID_HOST, FakeHueClient::VALID_TOKEN);

    expect($client->get('/unknown/endpoint'))->toBeEmpty();
});

it('throws exception if not authenticated', function (): void {
    $client = new FakeHueClient();

    $client->get('/resource/light');
})->throws(NotAuthenticatedException::class);