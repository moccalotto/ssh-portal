<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Terminal;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SshExecCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ssh:exec')
            ->setDescription('Execute a command on server')
            ->addArgument(
                'server',
                InputArgument::REQUIRED,
                'Name (or address) of the server'
            )
            ->addArgument(
                'remote-command',
                InputArgument::REQUIRED,
                'The command to be executed'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch a single server.
        $server = ServerList::fetchOne($input->getArgument('server'));

        $output->writeln('<info>Running command</info>');

        // execute the ssh command
        Terminal::execute(sprintf(
            'ssh -l %s %s "%s"',
            $server->username,
            $server->address,
            $input->getArgument('remote-command')
        ));

        $output->writeln('<info>Done</info>');
    }
}
