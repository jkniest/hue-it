<?php

use Illuminate\Support\Collection;
use jkniest\HueIt\Fake\FakeHueClient;
use jkniest\HueIt\PhillipsHue;

it('can use different clients', function () {
    $fakeClient = new FakeHueClient();

    $hue = new PhillipsHue();
    $hue->setClient($fakeClient);

    expect($hue->getClient())->toBe($fakeClient);
});

todo('it uses the local client by default');
