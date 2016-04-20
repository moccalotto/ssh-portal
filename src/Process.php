<?php

namespace Moccalotto\SshPortal;

use RuntimeException;

class Process extends Singletonian
{
    /**
     * Run the command in a simulated shell.
     *
     * @param string $stdinString The stdin to feed the program
     *
     * @return array [stdout, stderr, exitcode]
     */
    public function doExecute(string $command, string $stdinString)
    {
        $inpipes = [
            0 => ['pipe', 'r'],  // stdin
            1 => ['pipe', 'w'],  // stdout
            2 => ['pipe', 'w'],  // stderr
        ];

        $process = proc_open($command, $inpipes, $outpipes);

        if (!is_resource($process)) {
            throw new RuntimeException(sprintf('Could not execute command "%s"', $command));
        }

        list($stdin, $stdout, $stderr) = $outpipes;

        fwrite($stdin, $stdinString);
        fclose($stdin);

        return [
            'stdout' => stream_get_contents($stdout),
            'stderr' => stream_get_contents($stderr),
            'exitcode' => proc_close($process),
        ];
    }
}
