<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Cache;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PortalClearCacheCommand extends Command
{
    protected function configure()
    {
        $this->setName('portal:clear-cache')
            ->setDescription('Clear the server-list cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Cache::clear();
        $output->writeln('<info>Cache cleared</info>');
    }
}
