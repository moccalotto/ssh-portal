<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Terminal;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FileMonitorCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('file:monitor')
            ->setDescription('Display last X lines of a file and pipe all new output to screen')
            ->addArgument(
                'server',
                InputArgument::REQUIRED,
                'Name (or address) of the server'
            )->addArgument(
                'remote-path',
                InputArgument::REQUIRED,
                'Path of the remote file'
            )->addOption(
                'lines',
                null,
                InputOption::VALUE_REQUIRED,
                'How many lines of text should we start by fetching? [default=5]',
                '5'
            )->addOption(
                'once',
                null,
                InputOption::VALUE_NONE,
                'Should we only display the last few lines of the file and then exit?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch a single server.
        $server = ServerList::fetchOne($input->getArgument('server'));

        $once = $input->getOption('once');
        $lines = $input->getOption('lines');
        $file = $input->getArgument('remote-path');

        $output->writeln(sprintf('<info>Fetching last %d lines from "%s"</info>', $lines, $file));
        $output->writeln(sprintf('<info>%s</info>', $once ? 'Closing file after fetching' : 'Keeping file open'));

        $command = sprintf('tail %s -n %d \'%s\'', $once ? '' : '-F', $lines, $file);

        // execute the ssh command
        Terminal::execute(sprintf(
            'ssh -l %s %s "%s"',
            $server->username,
            $server->address,
            $command
        ));
    }
}
