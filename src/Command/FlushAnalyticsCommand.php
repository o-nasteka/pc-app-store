<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FlushAnalyticsCommand extends Command
{
    // protected static $defaultName = 'analytics:flush';

    public function __construct(private Connection $connection)
    {
        parent::__construct('analytics:flush');
    }

    protected function configure()
    {
        $this->setDescription('Delete all activity statistics.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->connection->executeStatement('DELETE FROM activities');
        $output->writeln('<info>All activity statistics have been deleted.</info>');
        return Command::SUCCESS;
    }
} 