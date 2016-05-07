# ssh-portal

Fast and easy way to connect to one of your servers.

ssh-portal makes it easy to keep a catalogue of all your servers and quickly connect to them.


## Installation

Requirements: make sure that you have composer installed.

We assume that you have a global compoaser installation,
and that ~/.composer/vendor/bin is in your PATH variable.
This is not a requirement, but it will make things a lot easier.

First, install the program.

```shell
$  composer global require moccalotto/ssh-portal
```


Then configure it:

```shell
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
            'vendor' => 'Uri',
            'name' => 'from-json-file',
            // uri can be anything support by php stream wrappers
            // see http://php.net/manual/wrappers.php
            'uri' => 'https://www.my-domain.tld/server-list.json',
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
                /* ... */
            ],
        ],
        /* ... */
    ],
];
```

## Usage

See all the available commands here:

```shell
$  ssh-portal list
```

See all your servers here.

```shell
$  ssh-portal server:list
```

Now it's time to play around.

## How do I access the servers?

It would be a good idea to use an SSH key that is authorized on your servers.
Otherwise, you will be prompted for a password whenever you try to connect.
You can use the `ssh-portal ssh:copy-id` command to install your ssh key on
the remote host.

Currently, we assume that you connect as root to all servers fetched from Hetzner and Digital Ocean.

If you need to use a different username, then, for now, you'd have to hardcode the server info in the
OnFile provider.
