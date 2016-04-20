<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Terminal;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FilePutCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('file:put')
            ->setDescription('Upload a file or directory to the server (using rsync)')
            ->addArgument(
                'server',
                InputArgument::REQUIRED,
                'Name (or address) of the server'
            )->addArgument(
                'source',
                InputArgument::REQUIRED,
                'Path of the local (source) file/directory'
            )->addArgument(
                'target',
                InputArgument::OPTIONAL,
                'Path of the remote (target) file/directory',
                ''
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch a single server.
        $server = ServerList::fetchOne($input->getArgument('server'));

        $sourcePath = $input->getArgument('source');
        $targetPath = $input->getArgument('target');

        if (!file_exists($sourcePath)) {
            throw new RuntimeException(sprintf(
                'Invalid path: "%s"',
                $sourcePath
            ));
        }

        if (!is_readable($sourcePath)) {
            throw new RuntimeException(sprintf(
                'Path not readable: "%s"',
                $sourcePath
            ));
        }

        $output->writeln(sprintf('<info>Copying from local to remote</info>'));

        Terminal::execute(sprintf(
            'rsync -a -h --delete %s %s@%s:%s',
            $sourcePath,
            $server->username,
            $server->address,
            $targetPath
        ));

        $output->writeln('<info>Done</info>');
    }
}
