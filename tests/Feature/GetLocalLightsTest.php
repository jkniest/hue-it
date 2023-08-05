<?php

use Illuminate\Support\Collection;
use jkniest\HueIt\Exceptions\NotAuthenticatedException;
use jkniest\HueIt\Fake\FakeHueClient;
use jkniest\HueIt\Fake\Model\FakeLight;
use jkniest\HueIt\Models\Light;
use jkniest\HueIt\PhillipsHue;

it('can return a collection of lights', function () {
    $client = new FakeHueClient();

    $client->setFakeLights([
        FakeLight::create()->name('Example Light 1'),
        FakeLight::create(),
        FakeLight::create(),
    ]);

    expect($client->isAuthenticated())->toBeFalse();

    $hue = new PhillipsHue();
    $hue->setClient($client);

    $authResult = $hue->authenticate(FakeHueClient::VALID_HOST, FakeHueClient::VALID_TOKEN);
    expect($authResult)->toBe($hue)
        ->and($client->isAuthenticated())->toBeTrue();

    $lights = $hue->getLights();

    expect($lights)->toBeInstanceOf(Collection::class)
        ->and($lights)->toHaveCount(3)
        ->and($lights->first())->toBeInstanceOf(Light::class)
        ->and($lights->first()->name)->toBe('Example Light 1');
});

it('throws an exception if not authenticated', function () {
    $hue = new PhillipsHue();
    $hue->setClient(new FakeHueClient());

    $hue->getLights();
})->throws(NotAuthenticatedException::class);
