<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Terminal;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FilePrintCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('file:print')
            ->setDescription('Print the contents of a remote file')
            ->addArgument(
                'server',
                InputArgument::REQUIRED,
                'Name (or address) of the server'
            )
            ->addArgument(
                'remote-path',
                InputArgument::REQUIRED,
                'Path of the remote file'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch a single server.
        $server = ServerList::fetchOne($input->getArgument('server'));

        $output->writeln('<info>Printting</info>');

        // execute the ssh command
        Terminal::execute(sprintf(
            'ssh -l %s %s "cat \'%s\'"',
            $server->username,
            $server->address,
            $input->getArgument('remote-path')
        ));
    }
}
