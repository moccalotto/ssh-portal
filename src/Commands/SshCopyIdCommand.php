<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Terminal;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SshCopyIdCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ssh:copy-id')
            ->setDescription('Copy your primay ssh key to the server')
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

        $output->writeln('<info>Copying your rsa key</info>');

        // execute the ssh command
        Terminal::execute(
            sprintf('ssh-copy-id %s@%s:', $server->username, $server->address)
        );

        $output->writeln('<info>Done</info>');
    }
}
