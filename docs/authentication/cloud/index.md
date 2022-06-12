# Cloud authentication

Use can use cloud authentication to control your lights from anywhere in the world. You don't need physical access
to the bridge for this part.

We recommend that you'll read the basics of [OAuth2](https://oauth.net/2/), since it will not be covered in this chapter.

## Requirements
You'll need to create a [Phillips Hue Developer Account](https://developers.meethue.com/). This is a
separate account from your regular Phillips Hue Account.

Also, you need to create a Hue-App. After you've signed in, you can see all your apps or create
new ones [here](https://developers.meethue.com/my-apps/):
- App name can be chosen freely
- Callback URL is the [OAuth2 callback url](https://oauth.net/2/grant-types/authorization-code/) where the user is redirected after authenticating
- Application description can be chosen freely

After you've created your app, you will have access to the "Client ID", "Client Secret" and "App ID". We'll need every
three parameters later on.

## Getting the user to authenticate
The first step for your cloud control is to authenticate against your newly created app.
The user must undergo the typical OAuth2 process. Meaning, they will be redirected to the Hue platform
itself, where they need to sign in. Phillips Hue later redirects the user back to the specified callback URL.

In this redirect, a new query parameter will be given, named `code`. This is a short-lived code that can be used to get access and refresh tokens.

Also, you may pass a "state". This is a freely choosable value that will be appended to the callback. This can be
used to identify the user or reauthenticate them.

In the following example we'll generate the OAuth2 url and redirect the user:

```php
use jkniest\HueIt\PhillipsHueCloud;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;

// Here you need to pass your client id and secret
$client = new HueClient('id', 'secret');

// The device id and name can be chosen freely. It will be stored on the bridge of the
// authenticating user to identify which device has connected. 
$device = new HueDevice('id', 'name');

// Here you need to pass the client and device. And as the third parameter you'll need
// to give the generated app id.
$hue = new PhillipsHueCloud($client, $device, 'app-id');

// This method generates the OAuth2 url. The user must be redirected there.
// You can pass any state value in there. (Probably a user token or something)
$oAuthUrl = $hue->getOAuthUrl('state');

// Finally, we redirect the user. If you are working in a framework, like Laravel or Symfony
// please use their redirect mechanisms.
header('Location: ' . $oAuthUrl);
```

## Request the access and refresh token

After the user is redirected back to your specified callback URL, a query parameter named `code` will
be transmitted. You can use this to get the access and refresh token. After authenticating you'll need to store these
tokens somewhere for the next requests.

Also, we need to generate a username. In the context of Phillips Hue,  the username is more like an API token. You can generate one and reuse it later on. So after creating the username please store it somewhere.

```php
use jkniest\HueIt\PhillipsHueCloud;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;

$client = new HueClient('id', 'secret'); 
$device = new HueDevice('id', 'name');

$hue = new PhillipsHueCloud($client, $device, 'app-id');

// Here we fetch the code from the query parameters. If you are using a framework like
// Laravel or Symfony, please use their input handling.
$code = $_GET['code'];

// Now with the code we can authenticate the user to get the tokens
$tokens = $hue->authenticate($code);

// With the tokens set, we can create the username
$username = $hue->createUsername();

// Finally we need to store these tokens somewhere
$accessToken = $tokens->getAccessToken();
$refreshToken = $tokens->getRefreshToken();
$username = $hue->getUsername();
```

## Reuse your tokens and username
After you get your tokens and username you probably want to reuse them. Otherwise, the user would need to undergo
the cloud process every time.

Here is an example of how those values can be used:

```php
use jkniest\HueIt\PhillipsHueCloud;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;

$client = new HueClient('id', 'secret'); 
$device = new HueDevice('id', 'name');

$hue = new PhillipsHueCloud($client, $device, 'app-id');

// We can use the `useTokens` method to pass in the access
// and refresh tokens.
$hue->useTokens('access-token', 'refresh-token');

// Also we can use the `useUsername` method to pass in the usename.
$hue->useUsername('username');

// Finally we have control over all lights and groups!
$hue->getAllLights()->each->turnOn();
```

## Refresh your access token
Access tokens are usually short-lived. They expire after some hours. So, you'll need to
refresh them. The hue-it library makes it super easy. After you passed in your old
access and refresh token, you'll need to call a refresh method.

Afterward, you get new tokens. Don't forget to override your old tokens
for later usage.

```php
use jkniest\HueIt\PhillipsHueCloud;
use jkniest\HueIt\Cloud\HueClient;
use jkniest\HueIt\Cloud\HueDevice;

$client = new HueClient('id', 'secret'); 
$device = new HueDevice('id', 'name');

$hue = new PhillipsHueCloud($client, $device, 'app-id');
$hue->useTokens('access-token', 'refresh-token');

// You can just call the "refresh" method on the tokens instance.
$newTokens = $hue->getTokens()->refresh();

// Now you can access the new tokens and store them somewhere
$accessToken = $newTokens->getAccessToken();
$refreshToken = $newTokens->getRefreshToken();
```
