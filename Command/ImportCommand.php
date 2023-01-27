<?php

namespace Sherlockode\ConfigurationBundle\Command;

use Sherlockode\ConfigurationBundle\Manager\ImportManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Kernel;

class ImportCommand extends Command
{
    protected static $defaultName = 'sherlockode:configuration:import';
    protected static $defaultDescription = 'Import configuration parameters from a source file';

    /**
     * @var ImportManagerInterface
     */
    private $importManager;

    /**
     * @param ImportManagerInterface $importManager
     */
    public function __construct(ImportManagerInterface $importManager)
    {
        parent::__construct();
        $this->importManager = $importManager;
    }

    public function configure()
    {
        $this
            ->addArgument(
                'filePath',
                InputArgument::OPTIONAL,
                'The source file path'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Start importing configuration...');
        $this->importManager->import($input->getArgument('filePath'));
        $io->success('Configuration successfully imported');

        if (Kernel::VERSION_ID >= 50100) {
            return Command::SUCCESS;
        }

        return 0;
    }
}
