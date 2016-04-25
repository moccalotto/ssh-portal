<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Terminal;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FileGetCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('file:get')
            ->setDescription('Download a file or directory from the server (using rsync)')
            ->addArgument(
                'server',
                InputArgument::REQUIRED,
                'Name (or address) of the server'
            )->addArgument(
                'source',
                InputArgument::REQUIRED,
                'Path of the remote (source) file/directory'
            )->addArgument(
                'target',
                InputArgument::OPTIONAL,
                'Path of the local (target) file/directory',
                '.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch a single server.
        $server = ServerList::fetchOne($input->getArgument('server'));

        $sourcePath = $input->getArgument('source');
        $targetPath = $input->getArgument('target');

        $output->writeln(sprintf('<info>Copying from remote to local</info>'));

        Terminal::execute(sprintf(
            'rsync -a -h --progress --delete  %s@%s:%s %s',
            $server->username,
            $server->address,
            $sourcePath,
            $targetPath
        ));

        $output->writeln('<info>Done</info>');
    }
}
