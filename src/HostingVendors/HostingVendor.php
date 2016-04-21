<?php

namespace Moccalotto\SshPortal\HostingVendors;

interface HostingVendor
{
    /**
     * Fetch a list of servers from the given vendor.
     */
    public static function fetchServers(array $credentials);
}
