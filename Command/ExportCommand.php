<?php

namespace Sherlockode\ConfigurationBundle\Command;

use Sherlockode\ConfigurationBundle\Manager\ExportManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Kernel;

class ExportCommand extends Command
{
    protected static $defaultName = 'sherlockode:configuration:export';
    protected static $defaultDescription = 'Export configuration parameters to the vault';

    private ExportManagerInterface $exportManager;

    public function __construct(ExportManagerInterface $exportManager)
    {
        parent::__construct();
        $this->exportManager = $exportManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Start exporting configuration...');
        $this->exportManager->exportInVault();
        $io->success('Configuration successfully sealed in vault');

        if (Kernel::VERSION_ID >= 50100) {
            return Command::SUCCESS;
        }

        return 0;
    }
}
