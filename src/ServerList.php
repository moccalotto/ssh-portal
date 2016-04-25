<?php

namespace Moccalotto\SshPortal;

use RuntimeException;

class ServerList extends Singletonian
{
    /**
     * @var Server[]
     */
    protected $servers = [];

    protected function __construct()
    {
        if (!$this->loadFromCache()) {
            $this->loadFromVendors();
            Cache::set('server-list', $this->servers);
        }
    }

    protected function loadFromVendors()
    {
        $this->servers = [];

        foreach (Config::get('hosts') as $credentials) {
            $vendor = $credentials['vendor'];
            $class = "Moccalotto\\SshPortal\\HostingVendors\\{$vendor}Vendor";

            if (class_exists($class)) {
                $this->importServers($class::fetchServers($credentials));
                continue;
            }

            if (class_exists($vendor)) {
                $this->importServers($vendor::fetchServers($credentials));
                continue;
            }
        }
    }

    protected function loadFromCache()
    {
        $cachedServerList = Cache::get('server-list');

        if (!$cachedServerList) {
            return false;
        }

        $this->servers = [];
        foreach ($cachedServerList as $server) {
            $this->servers[] = new Server(
                $server['name'],
                $server['address'],
                $server['username'],
                $server['hostingVendor']
            );
        }

        return true;
    }

    /**
     * Import array of servers.
     *
     * @param Server[] $servers
     *
     * @return $this
     */
    protected function importServers(array $servers)
    {
        foreach ($servers as $server) {
            $this->servers[] = $server;
        }

        return $this;
    }

    public function doFetchMatcing($pattern)
    {
        if ('' == $pattern) {
            return [];
        }

        if ('*' === $pattern) {
            return $this->servers;
        }

        $servers = [];

        foreach ($this->servers as $server) {
            if ($server->identifier === $pattern) {
                return [$server];
            }

            if (stripos($server->name, $pattern) !== false) {
                $servers[] = $server;
            } elseif (stripos($server->address, $pattern) !== false) {
                $servers[] = $server;
            } elseif (stripos($server->identifier, $pattern) !== false) {
                $servers[] = $server;
            }
        }

        return $servers;
    }

    public function doFetchOne($pattern)
    {
        $servers = $this->doFetchMatcing($pattern);

        if (count($servers) === 0) {
            throw new RuntimeException(sprintf(
                'No servers matching the pattern »%s« was found',
                $pattern
            ));
        }

        if (count($servers) > 1) {
            throw new RuntimeException(sprintf(
                "Too many servers matching the pattern »%s« was found: \n    %s",
                $pattern,
                implode("\n    ", $servers)
            ));
        }

        return $servers[0];
    }
}
