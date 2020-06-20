<?php

namespace PHPSTORM_META {
    expectedArguments(
        \jkniest\HueIt\Light::setEffect(),
        0,
        'none',
        'colorloop'
    );

    expectedArguments(
        \jkniest\HueIt\Light::setAlert(),
        0,
        'none',
        'select',
        'lselect'
    );

    expectedArguments(
        \jkniest\HueIt\Cloud\CloudHueClient::rawRequest(),
        0,
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );

    expectedArguments(
        \jkniest\HueIt\Cloud\CloudHueClient::request(),
        0,
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );

    expectedArguments(
        \jkniest\HueIt\Cloud\CloudHueClient::authRequest(),
        0,
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );

    expectedArguments(
        \jkniest\HueIt\Cloud\CloudHueClient::userRequest(),
        0,
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );

    expectedArguments(
        \jkniest\HueIt\Cloud\CloudHueClient::lightRequest(),
        0,
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );

    expectedArguments(
        \jkniest\HueIt\Local\LocalHueClient::rawRequest(),
        0,
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );

    expectedArguments(
        \jkniest\HueIt\Local\LocalHueClient::request(),
        0,
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );

    expectedArguments(
        \jkniest\HueIt\Local\LocalHueClient::userRequest(),
        0,
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );

    expectedArguments(
        \jkniest\HueIt\Local\LocalHueClient::lightRequest(),
        0,
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );
}
