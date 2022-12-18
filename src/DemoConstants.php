<?php

declare(strict_types=1);

namespace jkniest\HueIt;

class DemoConstants
{
    public const LIGHT_DATA = [
        'state'        => [
            'on'        => true,
            'bri'       => 156,
            'hue'       => '41435',
            'sat'       => 77,
            'effect'    => 'none',
            'xy'        => [
                0.1234,
                0.5678,
            ],
            'ct'        => 380,
            'colormode' => 'xy',
            'reachable' => false,
            'alert'     => 'lselect',
        ],
        'name'         => 'Example light 1',
        'capabilities' => [
            'control' => [
                'ct' => [
                    'min' => 200,
                    'max' => 800,
                ],
            ],
        ],
    ];

    public const CONFIG_DATA = [
        'name'          => 'Bridge name',
        'zigbeechannel' => 20,
        'modelid'       => 'BSB002',
        'apiversion'    => '1.37.0',
        'linkbutton'    => false,
        'whitelist'     => [
            'd649c993-0bed-4865-9494-1b06aa412704' => [
                'last use date' => '2018-10-17T01:06:14',
                'create date'   => '2017-12-28T17:16:10',
                'name'          => 'Example device',
            ],
            '40506dae-dd6a-4699-9546-bca83d19de57' => [
                'last use date' => '2020-04-29T19:05:53',
                'create date'   => '2020-04-29T19:01:04',
                'name'          => 'Another',
            ],
        ],
    ];

    public const GROUP_DATA = [
        'name'   => 'Example group 1',
        'lights' => [
            '2',
            '5',
        ],
        'type'   => 'Room',
        'state'  => [
            'all_on' => false,
            'any_on' => true,
        ],
        'class'  => 'Bedroom',
        'action' => [
            'on'        => false,
            'bri'       => 123,
            'hue'       => 23804,
            'sat'       => 254,
            'effect'    => 'none',
            'xy'        => [
                0.2066,
                0.6725,
            ],
            'ct'        => 153,
            'alert'     => 'lselect',
            'colormode' => 'xy',
        ],
    ];
}
