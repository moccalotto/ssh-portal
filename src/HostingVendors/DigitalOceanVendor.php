<?php

namespace Moccalotto\SshPortal\HostingVendors;

use Moccalotto\SshPortal\Http;
use Moccalotto\SshPortal\Server;

class DigitalOceanVendor implements HostingVendor
{
    public static function fetchServers(array $credentials)
    {
        $url = $credentials['url'] ?? 'https://api.digitalocean.com/v2/droplets';

        $headers = [
            'Content-Type: application/json',
            sprintf('Authorization: Bearer %s', $credentials['token']),
        ];

        $response = Http::request($url, '', $headers);

        return static::makeServerList(
            $credentials['name'],
            json_decode($response)
        );
    }

    protected static function makeServerList($vendor, $data)
    {
        $results = [];
        foreach ($data->droplets as $droplet) {
            $results[] = new Server(
                $droplet->name,
                $droplet->networks->v4[0]->ip_address,
                'root',
                $vendor
            );
        }

        return $results;
    }
}
