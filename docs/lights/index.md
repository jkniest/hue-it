# Controlling lights

You can use the same API whether you use the local or cloud driver.

## Getting lights

There are two ways to get your lights. You can fetch all lights at once
or a single light, if you know the ID.

### Getting all lights
```php
// This returns a illuminate/collection of all lights.
$lights = $hue->getAllLights();

// You can now iterate through them or use any collection
// method. See also: https://laravel.com/docs/master/collections#available-methods

// Example: Turn on all lights
$lights->each->turnOn();

// Example: Only get the lights which are on.
$lights->filter(
    fn(Light $light) => $light->isOn()
);

// Of course you can also use a traditional loop
foreach ($lights as $light) {
    $light->setBrightness(50);
}
```

### Getting specific light
```php
// This returns a single light with the ID 3
$light = $hue->getLight(3);

// Example: Turn on light
$light->turnOn();
```

## On / Off

You can turn lights on or off with the `setOn`-method:

```php
$light->setOn(true); // Turns the light on
$light->setOn(false); // Turns the light off
```

For your convenience we've also included some nice helper methods:
```php
$light->turnOn();
$light->turnOff();
```

To check if a light is on, there is a `isOn` method which return true or false, depending
of the light status:

```php
$isOn = $light->isOn();
```


## Brightness
By default, the brightness is between 0 and 254, where 0 is the darkest possible value
and 254 the brightest. You can still use those values, but by default, you can just
pass a percentage value (0-100) and hue-it automatically converts it for you!

```php
// Set the brightness in percent (0% - 100%)
$light->setBrightness(50);

// Set the brightness as an absolute value (0 - 254)
$light->setBrightness(127, true);
```

You can also get the brightness in percent or absolute:

```php
// Get the brightness in percent (0% - 100%)
$percent = $light->getBrightness();

// Get the brightness in absolut values (0 - 254)
$absolute = $light->getBrightness(true);
```

## Color

By default the Phillips Hue supports two color-modes:
[XY and Hue](https://developers.meethue.com/develop/application-design-guidance/color-conversion-formulas-rgb-to-xy-and-back/).

hue-it provides wrappers to set/get the color in RGB or Hex-Code. Hue colors
are currently **not** supported by this library.

```php
// Here we set the light to red (R=255, G=0, B=0)
$light->setColorAsRGB(255, 0, 0);

// Here we set the light to green (#00ff00)
$light->setColorAsHex('#00ff00');

// And finally we can still use the original XY values:
$light->setColorAsXY(0.123, 0.456);
```

You can also get the color in all these formats. It's irrelevant which format
you used to set the colors. Also, you can get the current color mode. Which is either `hs` (hue) or `xy`.

```php
// This returns an array with the R, G and B values
$rgb = $light->getColorAsRGB();

// You may combine this with the PHP list operator:
[$red, $green, $blue] = $light->getColorAsRGB();

// This method returns the HEX-string of the current color
$hex = $light->getColorAsHex();

// And finally this method returns an array with the X and Y values
$xy = $light->getColorAsXY();
[$x, $y] = $light->getColorsAsXY();

// To get the current color mode. This returns a string
$colorMode = $light->getColorMode();
```

## Color temperature (CT)

By default, the color temperature is between 153 and 500, where 0 is the coldest possible value
and 500 the warmest. You can still use those values, but by default, you can just
pass a percentage value (0-100) and hue-it automatically converts it for you!

```php
// Set the color temperature in percent (0% - 100%). 0% is the coldest possible value.
$light->getColorTemperature(50);

// Set the color temperature as an absolute value (153 - 500)
$light->setColorTemperature(220, true);
```

You can also get the color temperature in percent or absolute:

```php
// Get the color temperature in percent (0% - 100%)
$percent = $light->getColorTemperature();

// Get the color temperature in absolut values (0 - 254)
$absolute = $light->getColorTemperature(true);
```

## Saturation

By default, the saturation is between 0 and 254, where 0 is the most unsaturated value
and 254 the most. You can still use those values, but by default, you can just
pass a percentage value (0-100) and hue-it automatically converts it for you!

```php
// Set the saturation in percent (0% - 100%)
$light->setSaturation(50);

// Set the saturation as an absolute value (0 - 254)
$light->setSaturation(127, true);
```

You can also get the saturation in percent or absolute:

```php
// Get the saturation in percent (0% - 100%)
$percent = $light->getSaturation();

// Get the saturation in absolut values (0 - 254)
$absolute = $light->getSaturation(true);
```

## Effects and alerts

Phillips Hue supports both effects and alerts. You can use effects to start a `colorloop` which will iterate
through each color from time to time. Effects can be used to let your lights blink.

```php
// Set the effect. You can use "none" or "colorloop"
$light->setEffect('colorloop');

// Set the alert. Use can use "none", "select" and "lselect".
$light->setAlert('select');

// To disable effects or alerts you can just pass "none" to them:
$light->setEffect('none');
$light->setAlert('none');
```

You can also get the current active effect and alert:
```php
// This returns a string with the effect name
$effect = $light->getEffect();

// This returns a string with the alert name
$alert = $light->getAlert();
```


## Get Information

Other than controlling your lights you can also get some basic information about them.

```php
// You can get the name of the light
$name = $light->getName();

// You can get the ID of the light 
$id = $light->getId();

// Also you can check if the light is currently rechable by your bridge. This returns
// true if the light is reachable and false if not.
$rechable = $light->isReachable();
```

## Caching
After fetching the lights we don't automatically update their values. Normally in PHP, this is not
a big problem, since every request re-fetches the lights. But if you need to grab the new data directly
or you are running a long-running-process you can use the `refresh` method:

```php
$light->refresh();
```

This will update all values on the light object. Otherwise, you can just fetch the light from
your hue instance again. If you call `getLight` or `getAllLights` a second time, each light will be fetched again:

```php
$light = $hue->getLight(3);

// You can now do:
$light->refresh();

// OR

$light = $hue->getLight(3);
```

If you want to refresh all lights at once, it is more efficent to just regrab all lights again.
```php
$lights = $hue->getAllLights();

// This is quite slow, since it needs to do a single request for each light
foreach($lights as $light) {
    $light->refresh();
}

// This is more efficent:
$lights = $hue->getAllLights();
```

## Chaining

Each setter supports chaining. So you can pass multiple methods together:

```php
$light->turnOn()
      ->setBrightness(50)
      ->setColorAsRGB(255, 0, 234);
```