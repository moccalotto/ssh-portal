# Portal

Fast and easy way to connect to one of your servers.

Can connect to Hetzner and DigitalOcean to fetch lists of servers.

Based on the name/ip/description of these servers, you can select and connect to them very easily


## Installation

First, install the program.

```bash
$  composer global require moccalotto/ssh-portal
```


Then configure it:

```bash
$  ssh-portal portal:reset-config
```

This creates a config file here: `~/.ssh-portal.config.php`

Open this and add your servers in the config like so:

```php
<?php

return [
    // here, you define your hosting vendors.
    // currently, DigitalOcean and Hetzner are supported.
    // You can also define hosts "on file" via the OnFile provider.
    'hosts' => [
        /**
        [
            'vendor' => 'Hetzner',
            'name' => 'hetz-1',
            'user' => 'api-username-from-hetzner-robot',
            'pass' => 'api-upassword-from-hetzner-robot',
        ],
        [
            'vendor' => 'DigitalOcean',
            'name' => 'digo-1',
            'token' => 'token-from-digo-backoffice',
        ],
        [
            'vendor' => 'OnFile',
            'name' => 'my-own-datacenter',

            // Here you can add all the servers you own/operate
            // that are not located at one of the supported vendors.
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
];
```

## Usage

See all the available commands here:

```bash
$  ssh-portal list
```

See all your servers here.

```bash
$  ssh-portal server:list
```

Now it's time to play around.

## How do I access the servers?

ssh-portal assumes that you have an SSH key that is authorized by the server.
Otherwise, you will be prompted for a password whenever you try to connect.

Currently, we assume that you connect as root to all servers fetched from Hetzner and Digital Ocean.
