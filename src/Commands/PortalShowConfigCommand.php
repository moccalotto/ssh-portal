<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PortalShowConfigCommand extends Command
{
    protected function configure()
    {
        $this->setName('portal:show-config')
            ->setDescription('Print the configuration as json');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(json_encode(Config::all(), JSON_PRETTY_PRINT));
    }
}
