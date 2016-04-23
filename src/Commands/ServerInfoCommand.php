<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Process;
use Moccalotto\SshPortal\ServerList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerInfoCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('server:info')
            ->setDescription('Show all that we know about the server')
            ->addArgument(
                'server',
                InputArgument::REQUIRED,
                'Name (or address) of the server'
            )->addOption(
                'copy-ip',
                'c',
                InputOption::VALUE_NONE,
                'Copy the address of the server to pasteboard'
            );
    }

    protected function propertyLine($property, $value)
    {
        // <info>PROPERTY</info>        value
        return str_pad(sprintf('<info>%s</info>', $property), 12 + 15).$value;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch a single server.
        $server = ServerList::fetchOne($input->getArgument('server'));

        $ip = gethostbyname($server->address);
        $reverseDns = gethostbyaddr($ip);

        if ($input->getOption('copy-ip')) {
            Process::execute('pbcopy', $ip);
        }

        $output->writeln(sprintf('<info>Information for %s</info>', $server->name));
        $output->writeln('-------------------------------------');
        $output->writeln($this->propertyLine('Data Center', $server->hostingVendor));
        $output->writeln($this->propertyLine('Name', $server->name));
        $output->writeln($this->propertyLine('IP', $ip));
        $output->writeln($this->propertyLine('Reverse DNS', $reverseDns));
        $output->writeln($this->propertyLine('SSH Username', $server->username));
    }
}
