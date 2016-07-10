<?php

namespace Moccalotto\SshPortal\HostingVendors;

use Moccalotto\SshPortal\Server;

class OnFileVendor implements HostingVendor
{
    public static function fetchServers(array $credentials)
    {
        return static::makeServerList($credentials['name'], $credentials['servers']);
    }

    protected static function makeServerList($vendor, $servers)
    {
        $results = [];
        foreach ($servers as $server) {
            $results[] = new Server(
                $server['name'],
                $server['address'],
                $server['username'] ?? 'root',
                $vendor
            );
        }

        return $results;
    }
}
