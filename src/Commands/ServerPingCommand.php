<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Terminal;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerPingCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('server:ping')
            ->setDescription('Ping the server')
            ->addArgument(
                'server',
                InputArgument::REQUIRED,
                'Name (or address) of the server'
            )->addOption(
                'count',
                'c',
                InputOption::VALUE_REQUIRED,
                'Terminate after N pings. If 0, then do not terminate. [Default = 4]',
                4
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch a single server.
        $server = ServerList::fetchOne($input->getArgument('server'));

        $count = $input->getOption('count');

        if ($count == 0) {
            $output->writeln('<info>Pinging</info>');
            Terminal::execute(sprintf('ping %s', $server->address));
        } else {
            $output->writeln(sprintf('<info>Pinging %d times</info>', $count));
            Terminal::execute(sprintf('ping -c %d %s', $count, $server->address));
        }
    }
}
