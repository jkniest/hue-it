# PHP wrapper for the Phillips Hue API

{{drone}}

## Installation

Simply install this package via composer:
```shell script
composer require jkniest/hue-it
```

## Usage
### Local network

#### Authenticate

Before you can use the intergration you need to authenticate against your local bridge.
This can be done in two ways. If you already have a valid username, you may pass it as the
second argument in the constructor:

```php
use jkniest\HueIt\PhillipsHue;

$hue = new PhillipsHue('192.168.xxx.xx', 'secret-username');
```

If you don't have an username yet, this library can generate a new one. First you'll need to press
the `LINK` button on your physical hue bridge. Afterwards you should call the `authenticate` method.
It will return the newly generated username. You should store this username for later usage.

```php
use jkniest\HueIt\PhillipsHue;

$hue = new PhillipsHue('192.168.xxx.xx');

// Press LINK button before executing the authenticate method.
$username = $hue->authenticate('Unique device name');
```

### Via Phillips Hue Cloud
- TODO

## Testing
Coming soon.


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mail@jkniest.de instead of using the issue tracker.

## Credits

- [Jordan Kniest](https://github.com/jkniest)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
