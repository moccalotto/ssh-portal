<?php

namespace Moccalotto\SshPortal\Commands;

// COMMANDS:
// * portal:clear-cache
// * portal:reset-config

return [
    new PortalClearCacheCommand(),
    new PortalResetConfigCommand(),
    new FileGetCommand(),
    new FileMonitorCommand(),
    new FilePrintCommand(),
    new FilePutCommand(),
    new ServerInfoCommand(),
    new ServerListCommand(),
    new ServerPingCommand(),
    new SshConnectCommand(),
    new SshCopyIdCommand(),
    new SshExecCommand(),
    new SshMountCommand(),
    new SshProxyCommand(),
];
