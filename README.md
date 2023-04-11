# PHP wrapper for the Phillips Hue API

![Test](https://github.com/jkniest/hue-it/actions/workflows/test.yml/badge.svg?branch=main)


## Installation

Simply install this package via composer:
```shell script
composer require jkniest/hue-it
```

## [Documentation](https://hue-it.jkniest.dev)
The full documentation can be found [here](https://hue-it.jkniest.dev).

## Getting started

You can use the hue-it wrapper for both local network connections
or using the cloud.

### Usage (Local bridge)

First, you'll need to figure out the IP address of your bridge. You can
find the IP address in your router.

Also, you need physical access to your bridge.

Below is an example of how we might connect to a bridge with the
IP address `123.456.78.9`. After calling the `authenticate` method
the application waits until you press the LINK button
on your bridge.

```php
use jkniest\HueIt\PhillipsHue;

$hue = new PhillipsHue('123.456.78.9');
$hue->authenticate('device'); // Press LINK button

$hue->getLight(3)->turnOn();
```

Of course, you can reuse the generated username. See [Local authentication](https://hue-it.jkniest.dev/authentication/local/)
for more information.

### Usage (Cloud)

Using the hue cloud is a little more complex than the local network.
First, you'll need to [create a Phillips hue app](https://developers.meethue.com/my-apps/).

After creating the app you'll have access to client credentials (id and secret)
and an app ID. We need both to connect via the cloud. The device id and name can be chosen
as you like.

We recommend that you'll read the basics of [OAuth2](https://oauth.net/2/)
before continuing.

For more information see [Cloud authentication](https://hue-it.jkniest.dev/authentication/cloud/).

```php
use jkniest\HueIt\PhillipsHueCloud;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;

$client = new HueClient('id', 'secret');
$device = new HueDevice('id', 'name');

$hue = new PhillipsHueCloud($client, $device, 'app-id');
$hue->getOAuthUrl('state'); // The user must open this url in the browser.

// Here you can use the code which came back from the OAuth process.
$hue->authenticate('code');

$hue->getLight(3)->turnOn();
```

## Testing
If you are writing tests while using hue-it, there are two possibilities if you don't want to make api
requests each time your test suite runs:

1. Mock the PhillipsHue instance
2. Create your own Fake PhillipsHueGateway.

Currently we don't have documentation for this, but each Gateway extends from
an abstract class and has its own client (Cloud Client VS Local client). You could
create a new Gateway class and / or client class which returns mocked or fake data.

Generally it is planned to provide a full test mode.

## PHP compatibility
Please use the following table to check which version can be used for your PHP version

| PHP Version | Newest hue-it version | Supported |
|-------------|-----------------------|-----------|
| v8.2        | v0.3.*                | ✅         |
| v8.1        | v0.3.*                | ✅         |
| v8.0        | v0.3.*                | ✅         |
| v7.4        | v0.2.*                | ❌         |

## Roadmap
- Test mode
- Creating / Deleting / Editing groups and lights
- Capabilities API
- Update handling (Bridge & Light Updates)
- Implement more entities: Schedules, Scenes, Sensors, Rules, etc.
- Configure transition times

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mail@jkniest.de instead of using the issue tracker.

## Credits

- [Jordan Kniest](https://github.com/jkniest)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
