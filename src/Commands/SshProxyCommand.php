<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Terminal;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SshProxyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ssh:proxy')
            ->setDescription('Create a socks proxy through the server')
            ->addArgument(
                'server',
                InputArgument::REQUIRED,
                'Name (or address) of the server'
            )->addArgument(
                'port',
                InputArgument::OPTIONAL,
                'The local port of the proxy [default: 9999]',
                9999
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch a single server.
        $server = ServerList::fetchOne($input->getArgument('server'));
        $port = $input->getArgument('port');

        // give some info
        $output->writeln(sprintf('<info>Created tunnel to %s on port %d</info>', $server, $port));
        $output->writeln('<info>Proxy will remain open until you terminate this program</info>');

        // execute the ssh command
        Terminal::execute(
            sprintf(
                'ssh -N -D %d -l %s %s',
                $port,
                $server->username,
                $server->address
            )
        );

        $output->writeln(sprintf('<info>Port closed</info>', $server));
    }
}
