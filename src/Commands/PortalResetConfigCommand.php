<?php

namespace Moccalotto\SshPortal\Commands;

use Moccalotto\SshPortal\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class PortalResetConfigCommand extends Command
{
    protected function configure()
    {
        $this->setName('portal:reset-config')
            ->setDescription('Reset the config file')
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Reset the config, even though a config file already exists'
            );
    }

    protected function receivedConfirmation($input, $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Config file exists, overwrite? [y/N] ', false);

        return $helper->ask($input, $output, $question);
    }

    protected function requiresConfirmation($input)
    {
        if ($input->getOption('force')) {
            return false;
        }

        return is_file(Config::file());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $allow = $this->requiresConfirmation($input) === false || $this->receivedConfirmation($input, $output) === true;

        if (!$allow) {
            $output->writeln('<info>Aborting</info>');

            return;
        }

        Config::resetConfig();
        $output->writeln('<info>Config reset</info>');
    }
}
