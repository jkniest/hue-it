# Controlling groups

You can use the same API whether you use the local or cloud driver.

## Types of groups
Groups have different types. The most used are Room and Zone. Technically
there is no difference between a regular group and a room, so you can use the same API
for both. Further down we'll show an example of how to fetch only rooms.

## Getting groups

There are two ways to get your groups. You can fetch all groups at once
or a single group, if you know the ID.

### Getting all groups
```php
// This returns a illuminate/collection of all groups.
$groups = $hue->getAllGroups();

// You can now iterate through them or use any collection
// method. See also: https://laravel.com/docs/master/collections#available-methods

// Example: Turn on all lights across all groups
$groups->each->turnOn();

// Example: Get all rooms, but no other group types.
$rooms = $groups->filter(
    fn(Group $group) => $group->getType() === 'Room'
);

// Of course you can also use a traditional loop
foreach ($groups as $group) {
    $group->setBrightness(50);
}
```

### Getting specific group
```php
// This returns a single group with the ID 3
$group = $hue->getGroup(3);

// Example: Turn on all lights in group
$group->turnOn();
```

## Getting lights in group
hue-it provides two methods to access all lights within a group. You can either
only fetch all IDs or a collection of all lights.

```php
// This returns a collection of all IDs
$ids = $group->getLightIds();

// This returns a collection of all lights
$lights = $group->getLights();

// You can now use this collection the same way, you would
// use the collection if the `getAllLights` method:
$lights->each->turnOn();
```

## On / Off

You can turn all lights in the group on or off with the `setOn`-method:

```php
$group->setOn(true); // Turns all lights on
$group->setOn(false); // Turns all lights off
```

For your convenience we've also included some nice helper methods:
```php
$group->turnOn();
$group->turnOff();
```

To check if lights are on, there are two methods which can be used:

```php
// This returns true if ALL lights of the group are active
$allOn = $group->areAllOn();

// This returns true if ANY light in the group is active
$anyOn = $group->isAnyOn();
```


## Brightness
By default, the brightness is between 0 and 254, where 0 is the darkest possible value
and 254 the brightest. You can still use those values but by default, you can just
pass a percentage value (0-100) and hue-it automatically converts it for you!

```php
// Set the brightness for all lights in the group in percent (0% - 100%)
$group->setBrightness(50);

// Set the brightness as an absolute value (0 - 254)
$group->setBrightness(127, true);
```

You can also get the brightness. If the brightness levels are different across all
lights in your group this value will be the same as the last added light.

```php
// Get the brightness in percent (0% - 100%)
$percent = $group->getBrightness();

// Get the brightness in absolut values (0 - 254)
$absolute = $group->getBrightness(true);
```

If you want to have the brightness levels of **each** light in the group, you can use the collection methods:

```php
// This returns a collection with all the brightness values of each light.
$group->getLights()->map(
    fn(Light $light) => $light->getBrightness()
);

// The same can be accomplished with the loops
$brightnesses = [];
foreach($group->getLights() as $light) {
    $brightnesses[$light->getId()] = $light->getBrightness();
}
```

## Color

By default the Phillips Hue supports two color-modes:
[XY and Hue](https://developers.meethue.com/develop/application-design-guidance/color-conversion-formulas-rgb-to-xy-and-back/).

hue-it provides wrappers to set/get the color in RGB or Hex-Code. Hue colors
are currently **not** supported by this library.

```php
// Here we set all lights of the group to red (R=255, G=0, B=0)
$group->setColorAsRGB(255, 0, 0);

// Here we set all lights to green (#00ff00)
$group->setColorAsHex('#00ff00');

// And finally we can still use the original XY values:
$group->setColorAsXY(0.123, 0.456);
```

You can also get the color in all these formats. It's irrelevant which format
you used to set the colors. Also, you can get the current color mode. Which is either `hs` (hue) or `xy`.
If the colors are different across all lights in your group this value will be the same as the last added light.

```php
// This returns an array with the R, G and B values
$rgb = $group->getColorAsRGB();

// You may combine this with the PHP list operator:
[$red, $green, $blue] = $group->getColorAsRGB();

// This method returns the HEX-string of the current color
$hex = $group->getColorAsHex();

// And finally this method returns an array with the X and Y values
$xy = $group->getColorAsXY();
[$x, $y] = $group->getColorsAsXY();

// To get the current color mode. This returns a string
$colorMode = $group->getColorMode();
```

## Color temperature (CT)

By default, the color temperature is between 153 and 500, where 0 is the coldest possible value
and 500 the warmest.

> **Information**: Percentage color temperatures (0%  - 100%) are currently not supported
> within groups. Please use the collection methods or loops to set the lights individually.

```php
// Set the color temperature as an absolute value
$group->setColorTemperature(220);
```

You can also get the absolute color temperature. If the temperatures are different across all
lights in your group this value will be the same as the last added light.

```php
// Get the color temperature in absolut values
$colorTemperature = $group->getColorTemperature();
```

## Saturation

By default, the saturation is between 0 and 254, where 0 is the most unsaturated value
and 254 the most. You can still use those values but by default, you can just
pass a percentage value (0-100) and hue-it automatically converts it for you!

```php
// Set the saturation of all lights in your group in percent (0% - 100%)
$group->setSaturation(50);

// Set the saturation as an absolute value (0 - 254)
$group->setSaturation(127, true);
```

You can also get the saturation in percent or absolute. If the saturation levels are different across all
lights in your group this value will be the same as the last added light.

```php
// Get the saturation in percent (0% - 100%)
$percent = $group->getSaturation();

// Get the saturation in absolut values (0 - 254)
$absolute = $group->getSaturation(true);
```

## Effects and alerts

Phillips Hue supports both effects and alerts. You can use effects to start a `colorloop` which will iterate
through each color from time to time. Effects can be used to let your lights blink.

```php
// Set the effect for the whole group. You can use "none" or "colorloop"
$group->setEffect('colorloop');

// Set the alert. Use can use "none", "select" and "lselect".
$group->setAlert('select');

// To disable effects or alerts you can just pass "none" to them:
$group->setEffect('none');
$group->setAlert('none');
```

You can also get the current active effect and alert. If the values are different across all
lights in your group this value will be the same as the last added light.
```php
// This returns a string with the effect name
$effect = $group->getEffect();

// This returns a string with the alert name
$alert = $group->getAlert();
```


## Get Information

Other than controlling your groups you can also get some basic information about them.

```php
// You can get the name of the group
$name = $group->getName();

// You can get the ID of the group 
$id = $group->getId();

// You can get the type of the group. This is mostly "Room" or "Zone"
$type = $group->getType();

// Also each group has a "class". This is for example "Bathroom".
$class = $group->getClass();
```

## Caching
After fetching the groups we don't automatically update their values. Normally in PHP, this is not
a big problem, since every request re-fetches the groups. But if you need to grab the new data directly
or you are running a long-running-process you can use the `refresh` method:

```php
$group->refresh();
```

This will update all values on the group object. Otherwise, you can just re-fetch the group from
your hue instance. If you call `getGroup` or `getAllGroups` a second time, each group will be fetched again:

```php
$group = $hue->getGroup(3);

// You can now do:
$group->refresh();

// OR

$group = $hue->getGroup(3);
```

If you want to refresh all groups at once, it is more efficient to just grab all groups again.
```php
$groups = $hue->getAllGroups();

// This is quite slow, since it needs to do a single request for each group
foreach($groups as $group) {
    $group->refresh();
}

// This is more efficient:
$groups = $hue->getAllGroups();
```

## Chaining

Each setter supports chaining. So you can pass multiple methods together:

```php
$group->turnOn()
      ->setBrightness(50)
      ->setColorAsRGB(255, 0, 234);
```