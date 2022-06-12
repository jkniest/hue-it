# Bridge config

You can access a variety of bridge config values. Currently, all those values are
read-only.

## Get information

To access your bridge configuration you can use the `getConfig` method of your
hue driver. The API is the same for both local and cloud:

```php
// This returns an instance of the PhillipsHueConfig object.
$config = $hue->getConfig();

// Get the name of the bridge
$name = $config->getName();

// Get the used zig bee channel
$channel = $config->getZigBeeChannel();

// Get the model id of the bridge
$modelId = $config->getModelId();

// Get the current API version which runs on the bridge
$apiVersion = $config->getApiVersion();

// Check if the LINK button is currently pressed. Returns true
// if the button was pressed a short time ago
$linkButtonPressed = $config->isLinkButtonPressed();

// If you need any other config value, you can access the raw response
// from the hue api:
$rawData = $config->getRawData();
```

## Whitelist

By default, each authorized username / API token is stored in the whitelist of the
bridge. You can access the whitelist and inspect each device that has access.

```php
// This method returns a collection with all allowed devices.
$whitelist = $config->getWhitelist();

// You can now iterate through them:
foreach($whitelist as $device) {
echo $device->getName();
}
```

### Available methods on the whitelist
```
// Returns the name of the device
$name = $device->getName();

// Returns the ID of the device
$id = $device->getId();

// Returns a DateTime with the last use date and time
$lastUse = $device->getLastUseDate();

// Returns a DateTime with the creation date and time
$create = $device->getCreateDate();
```