# hue-it

hue-it is a PHP wrapper for the official Phillips Hue API. It supports both
local and cloud connections. You can use it to control your lights, groups, and general Phillips Hue configuration.

It's completely open-source and MIT licensed.. so do what you want with it!

## Getting started

You can use the hue-it wrapper for both local network connections
or using the cloud.

### Installation

Simply install this package via composer, and you're ready to go:

```shell script
composer require jkniest/hue-it
```

### Usage (Local bridge)

First, you'll need to figure out the IP address of your bridge. You can
find the IP address in your router.

Also, you need physical access to your bridge.

Below is an example of how we might connect to a bridge with the
IP address `123.456.78.9`. Before calling the `authenticate` method
to get a username, you'll need to press the LINK button your hue bridge.

```php
use jkniest\HueIt\PhillipsHue;

$hue = new PhillipsHue('123.456.78.9');
$hue->authenticate('device-name'); // Press LINK button

$hue->getLight(3)->turnOn();
```

Of course, you can reuse the generated username. See [Local authentication](/authentication/local/)
for more information.

### Usage (Cloud)

Using the hue cloud is a little more complex than the local network.
First, you'll need to [create a Phillips hue app](https://developers.meethue.com/my-apps/).

After creating the app you'll have access to client credentials (id and secret)
and an app ID. We need both to connect via the cloud. The device id and name can be chosen
as you like.

We recommend that you'll read the basics of [OAuth2](https://oauth.net/2/)
before continuing.

For more information see [Cloud authentication](/authentication/cloud/).

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

## PHP compatibility
Please use the following table to check which version can be used for your PHP version

| PHP Version | Newest hue-it version | Supported |
|-------------|-----------------------|-----------|
| v8.2        | Unreleased            | ✅         |
| v8.1        | v0.2.*                | ✅         |
| v8.0        | v0.2.*                | ✅         |
| v7.4        | v0.2.*                | ❌         |


## Known limitations
Currently, you can only control your lights and groups. Other configurations, such as the startup configuration, bridge updates, and so on are not supported yet. Feel free to create an issue
or pull-request if you think something important is missing!

### Other limitations
- Groups don't support setting the color temperature in percentage. Because in theory, every light could have different max and min color temperatures.
- Currently, we only support setting / getting the colors via XY, Hex, and RGB. You can not pass a hue value