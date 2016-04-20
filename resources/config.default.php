<?php

return [
    'cache' => [
        'timeout' => 900, // 15 minutes.
    ],

    // here, you define your hosting vendors.
    // currently, DigitalOcean and Hetzner are supported.
    // You can also define hosts "on file" via the OnFile provider.
    'hosts' => [
        /**
        [
            'vendor' => 'Hetzner',
            'name' => 'hetz-1',
            'user' => '',
            'pass' => '',
        ],
        [
            'vendor' => 'DigitalOcean',
            'name' => 'digo-1',
            'token' => '',
        ],
        [
            'vendor' => 'OnFile',
            'name' => 'my-own-datacenter',
            'servers' => [
                [
                    'name' => 'Some Server',
                    'address' => '123.123.123.123',
                    'username' => 'monkey',
                ],
                ...
            ],
        ]
        ...
         */
    ],

    // Http client settings:
    // See http://php.net/manual/en/context.http.php
    'http' => [
        'follow_location'=> true,
        'max_redirects'=> 0,
        'user_agent'=> 'SshPortal',
        'timeout'=> 10
    ],

    // Https client settings:
    // See http://php.net/manual/en/context.ssl.php
    'https' => [
        'verify_peer'=> true,
        'verify_peer_name'=> true,
        'allow_self_signed'=> false
    ]
];
