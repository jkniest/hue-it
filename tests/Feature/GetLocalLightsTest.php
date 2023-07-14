<?php

use Illuminate\Support\Collection;
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

    $hue = new PhillipsHue('123.456.789.1', 'my-token-123');
    $hue->setClient($client);

    $lights = $hue->getLights();

    expect($lights)->toBeInstanceOf(Collection::class)
        ->and($lights)->toHaveCount(3)
        ->and($lights->first())->toBeInstanceOf(Light::class)
        ->and($lights->first()->name)->toBe('Example Light 1');
});
