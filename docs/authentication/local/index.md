# Local authentication

To authenticate locally with your Phillips Hue bridge you need to be on the same Wi-Fi network as the bridge, and you need physical access to the bridge.

If you want to control your lights from outside your network, please see [Cloud authentication](/authentication/cloud/).

## Finding the IP address
Before you can start, you'll need to find the IP address of your bridge. You can find it in your
router. Otherwise, Phillips Hue has a guide for [Hue Bridge Discovery](https://developers.meethue.com/develop/application-design-guidance/hue-bridge-discovery/).

## First authentication
If you have never authenticated against your bridge, you'll need to create a username. In the context of Phillips Hue, the username is more like an API token. You can generate one and reuse it later on. So after creating the username you should store it somewhere for later usage.

```php
use jkniest\HueIt\PhillipsHue;

// Here you need to enter the IP address of your phillips hue bridge.
$hue = new PhillipsHue('123.456.78.9');

// For your first authentication you can now call the `authenticate` method.
// You need to pass in a device name. This can be chosen freely.
// You'll need to press the LINK button on your bridge before calling the
// authenticate method. 
$username = $hue->authenticate('device name');

// You can now store the username somewhere to reuse it later on.
// And also, you now have full access to your lights!
$hue->getAllLights()->each->turnOn();
```

## Reuse your username
After you got your username you probably want to reuse it. Otherwise, you would need to press
the LINK button every time your code restarts.

Here is an example of how the username can be used:

```php
use jkniest\HueIt\PhillipsHue;

// Here you need to enter the IP address of your Phillips Hue bridge.
// Additionally you can pass the username the hue instance.
$hue = new PhillipsHue('123.456.78.9', 'your-username');

// Now have full access to your lights!
$hue->getAllLights()->each->turnOn();

// Also you can get your username and ip address anytime again
$username = $hue->getUsername();
$ip = $hue->getIp();
```