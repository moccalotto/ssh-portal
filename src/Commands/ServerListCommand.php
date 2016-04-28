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
                'hosts-file',
                'H',
                InputOption::VALUE_NONE,
                'Print list in the format of a hosts file'
            )->addOption(
                'refresh-cache',
                'r',
                InputOption::VALUE_NONE,
                'Clear the cache before fetching the server list'
            );
    }

    protected function renderAsTable(OutputInterface $output, array $servers)
    {
        $rows = [];

        foreach ($servers as $server) {
            $rows[] = [
                $server->hostingVendor,
                $server->name,
                $server->username,
                $server->address,
            ];
        }
        (new Table($output))
            ->setHeaders(['DataCenter', 'Name', 'User', 'Address'])
            ->setRows($rows)
            ->render();
    }

    protected function renderAsHostsFile(OutputInterface $output, array $servers)
    {
        $rows = [];

        foreach ($servers as $server) {
            $rows[] = [
                $server->address . '    ',
                $server->name,
                '## ' . $server->hostingVendor
            ];
        }

        (new Table($output))
            ->setStyle('compact')
            ->setHeaders(['## IP', 'Name', 'DataCenter'])
            ->setRows($rows)
            ->render();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('refresh-cache')) {
            Cache::clear();
            $output->writeln('<info>Cache cleared. Fetching server list</info>');
            $output->writeln('');
        }

        // Fetch a single server.
        $servers = ServerList::fetchMatcing($input->getArgument('pattern'));

        return $input->getOption('hosts-file')
            ? $this->renderAsHostsFile($output, $servers)
            : $this->renderAsTable($output, $servers);

    }
}
