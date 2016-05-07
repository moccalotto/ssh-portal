<?php

namespace Moccalotto\SshPortal\HostingVendors;

use Moccalotto\SshPortal\Http;
use Moccalotto\SshPortal\Server;

class UriVendor implements HostingVendor
{
    public static function fetchServers(array $credentials)
    {
        $uri = $credentials['uri'];

        $response = file_get_contents($uri);

        return static::makeServerList(
            $credentials['name'],
            json_decode($response)
        );
    }

    protected static function makeServerList($vendor, $data)
    {
        $results = [];

        foreach ($data as $obj) {
            $results[] = new Server(
                $obj->name,
                $obj->address,
                $obj->username ?? 'root',
                $vendor
            );
        }

        return $results;
    }
}
