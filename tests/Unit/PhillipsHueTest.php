<?php

use jkniest\HueIt\Clients\LocalHueClient;
use jkniest\HueIt\Fake\FakeHueClient;
use jkniest\HueIt\PhillipsHue;

it('can use different clients', function () {
    $fakeClient = new FakeHueClient();

    $hue = new PhillipsHue();
    $hue->setClient($fakeClient);

    expect($hue->getClient())->toBe($fakeClient);
});

it('it uses the local client by default', function(): void {
    $hue = new PhillipsHue();

    expect($hue->getClient())->toBeInstanceOf(LocalHueClient::class);
});
