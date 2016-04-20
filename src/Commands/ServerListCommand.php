<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Cache;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerListCommand extends Command
{
    protected function configure()
    {
        $this->setName('server:list')
            ->setDescription('List servers')
            ->addArgument(
                'pattern',
                InputArgument::OPTIONAL,
                'Show only servers that match this pattern',
                '*'
            )->addOption(
                'refresh-cache',
                null,
                InputOption::VALUE_NONE,
                'Clear the cache before fetching the server list'
            );
    }

    protected function serverRows($servers)
    {
        $rows = [];
        foreach ($servers as $server) {
            $rows[] = [
                $server->hostingVendor,
                $server->name,
                sprintf('%s@%s', $server->username, $server->address),
            ];
        }

        return $rows;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('refresh-cache')) {
            Cache::clear();
            $output->writeln('<info>Cache refreshed</info>');
            $output->writeln('');
        }

        // Fetch a single server.
        $servers = ServerList::fetchMatcing($input->getArgument('pattern'));

        $table = new Table($output);

        $table->setHeaders(['Vendor', 'Name', 'Connection'])
            ->setRows($this->serverRows($servers))
            ->render();
    }
}
