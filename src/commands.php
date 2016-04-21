<?php

namespace Moccalotto\SshPortal\Commands;

// COMMANDS:
// * portal:clear-cache
// * portal:reset-config

return [
    new FileGetCommand(),
    new FileMonitorCommand(),
    new FilePrintCommand(),
    new FilePutCommand(),
    new PortalClearCacheCommand(),
    new PortalResetConfigCommand(),
    new PortalShowConfigCommand(),
    new ServerInfoCommand(),
    new ServerListCommand(),
    new ServerPingCommand(),
    new SshConnectCommand(),
    new SshCopyIdCommand(),
    new SshExecCommand(),
    new SshMountCommand(),
    new SshProxyCommand(),
];
