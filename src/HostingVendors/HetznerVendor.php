<?php

namespace Moccalotto\SshPortal\HostingVendors;

use Moccalotto\SshPortal\Http;
use Moccalotto\SshPortal\Server;

class HetznerVendor implements HostingVendor
{
    public static function fetchServers(array $credentials)
    {
        $url = $credentials['url'] ?? 'https://robot-ws.your-server.de/server';

        $auth = base64_encode(sprintf(
            '%s:%s',
            $credentials['user'],
            $credentials['pass']
        ));

        $response = Http::request($url, '', [
            sprintf('Authorization: Basic %s', $auth),
        ]);

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
                $obj->server->server_name,
                $obj->server->server_ip,
                'root',
                $vendor
            );
        }

        return $results;
    }
}
