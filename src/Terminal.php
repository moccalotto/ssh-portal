<?php

namespace Moccalotto\SshPortal;

class Terminal extends Singletonian
{
    /**
     * Run the command as if in a normal shell.
     *
     * The program receives the normal terminal stdin, stdout and stderr.
     */
    public function doExecute(string $command) : int
    {
        $inpipes = [
            0 => ['file', 'php://stdin', 'r'],
            1 => ['file', 'php://stdout', 'w'],
            2 => ['file', 'php://stderr', 'w'],
        ];

        $proc = proc_open($command, $inpipes, $outpipes);

        do {
            $status = proc_get_status($proc);
            usleep(20000);
        } while ($status['running']);

        return $status['exitcode'];
    }
}
