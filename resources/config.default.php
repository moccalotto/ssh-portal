<?php

return [
    'cache' => [
        'timeout' => 86400, // 24 hours
    ],

    // Define your hosting vendor integrations here.
    // Currently we support DigitalOcean and Hetzner.
    // You can add your own: just give a FQCN in the 'vendor' field,
    // make sure that your composer knows how to autoload it, and
    // you should be good to go.
    // HostingVendors SHOULD implement the Moccalotto\SshPortal\HostingVendors\HostingVendor interface,
    // however, this is not strictly enforced yet.
    //
    // You can also define hosts "on file" via the OnFile provider.
    'hosts' => [
        /*
        [
            'vendor' => 'Hetzner',
            'name' => 'hetz-1',
            'user' => getenv('HETZNER_API_USER'),
            'pass' => getenv('HETZNER_API_PASS'),
        ],
        [
            'vendor' => 'DigitalOcean',
            'name' => 'digo-1',
            'token' => getenv('DIGO_API_TOKEN'),
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
        'follow_location'=> false,
        'max_redirects'=> 0,
        'user_agent'=> 'SshPortal',
        'timeout'=> 10
    ],

    // Https client settings:
    // See http://php.net/manual/en/context.ssl.php
    'https' => [
        'verify_peer'=> true,
        'verify_peer_name'=> true,
        'allow_self_signed'=> false,
        'verify_depth' => 10,
    ]
];
