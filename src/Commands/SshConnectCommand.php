<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Terminal;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SshConnectCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ssh:connect')
            ->setDescription('Connect to a given server')
            ->addArgument(
                'server',
                InputArgument::REQUIRED,
                'Name (or address) of the server'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch a single server.
        $server = ServerList::fetchOne($input->getArgument('server'));

        // give some info
        $output->writeln(sprintf('<info>Connecting to %s</info>', $server));

        // execute the ssh command
        Terminal::execute(
            sprintf('ssh -l %s %s', $server->username, $server->address)
        );

        $output->writeln(sprintf('<info>Done</info>', $server));
    }
}
