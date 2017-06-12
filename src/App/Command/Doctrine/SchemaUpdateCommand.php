<?php

namespace App\Command\Doctrine;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Command which updates the application database schema.
 */
class SchemaUpdateCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('doctrine:schema-update')
            ->setDescription('Updates the application\'s database schema.')
            ->addOption('drop', 'd', InputOption::VALUE_NONE, 'If the schema should be dropped first.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $noExecutedInstructions = $this->container
            ->get('doctrine.schema_manager')
            ->update($input->getOption('drop'));

        $output->writeln(sprintf('<info>Number of executed queries: %d</info>', $noExecutedInstructions));
    }
}
