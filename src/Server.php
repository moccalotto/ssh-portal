<?php

namespace Moccalotto\SshPortal;

/**
 * Server info DTO.
 */
class Server
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $hostingVendor;

    /**
     * @var string
     */
    public $identifier;

    public function __construct(string $name, string $address, string $username, string $hostingVendor)
    {
        $this->name = $name;
        $this->address = $address;
        $this->username = $username;
        $this->hostingVendor = $hostingVendor;
        $this->identifier = sprintf('%s:%s', $hostingVendor, $name);
    }

    public function __toString() : string
    {
        return $this->identifier;
    }
}
