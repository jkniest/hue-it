<?php

use jkniest\HueIt\Clients\LocalHueClient;

todo('can authenticate', function (): void {
    $client = new LocalHueClient();

    expect($client->isAuthenticated())->toBeFalse();

    $client->authenticate('127.0.0.1', 'my-token-123');

    expect($client->isAuthenticated())->toBeTrue()
        ->and($client->getHost())->toBe('127.0.0.1')
        ->and($client->getToken())->toBe('my-token-123');
});

todo('can fetch resources');

todo('it throws an exception if not authenticated');

todo('it throws an exception if the bridge is not reachable');

todo('it throws an exception if the resource is not found');

todo('it throws an exception if the request is invalid');