<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Terminal;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SshMountCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ssh:mount')
            ->setDescription('Mount a directory on the server')
            ->addArgument(
                'server',
                InputArgument::REQUIRED,
                'Name (or address) of the server'
            )
            ->addArgument(
                'remote',
                InputArgument::OPTIONAL,
                'The remote dir to be mounted [default /]',
                '/'
            )
            ->addArgument(
                'local',
                InputArgument::OPTIONAL,
                'The local mount point',
                '/Volumes/[server-name]'
            )->addOption(
                'open',
                null,
                InputOption::VALUE_NONE,
                'Open the mount point after a successful mount'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // WARNING: this command is extremely OSX specific.
        if (PHP_OS !== 'Darwin') {
            $output->writeln('<error>This command only works on OSX');

            return -1;
        }

        $server = ServerList::fetchOne($input->getArgument('server'));
        $remote_dir = $input->getArgument('remote');
        $local_dir = str_replace('[server-name]', $server->name, $input->getArgument('local'));

        if (!is_dir($local_dir)) {
            mkdir($local_dir);
        }

        $output->writeln(sprintf('<info>Mounting</info>', $server));

        Terminal::execute(sprintf(
            'sshfs %s@%s:"%s" "%s"',
            $server->username,
            $server->address,
            $remote_dir,
            $local_dir
        ));

        // open the folder
        if ($input->getOption('open')) {
            system(sprintf('open "%s"', $local_dir));
        }

        $output->writeln(sprintf('<info>Done</info>', $server));
    }
}
