<?php

use jkniest\HueIt\Fake\Model\FakeLight;

it('has default values', function (): void {
    $light = FakeLight::create();

    expect($light->id)->toBe('fake-id')
        ->and($light->name)->toBe('Fake Light');
});

it('can set the id', function (): void {
    $light = FakeLight::create()->id('id-123');

    expect($light->id)->toBe('id-123');
});

it('can set the name', function (): void {
    $light = FakeLight::create()->name('Test Light');

    expect($light->name)->toBe('Test Light');
});

it('can convert the fake light into a valid array', function (): void {
    $light = FakeLight::create();

    $array = $light->toArray();

    expect($array)->toBeArray()
        ->toHaveKeys(['id', 'metadata.name'])
        ->and($array['id'])->toBe('fake-id')
        ->and($array['metadata']['name'])->toBe('Fake Light');
});