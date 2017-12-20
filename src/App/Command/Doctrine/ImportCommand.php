<?php

namespace App\Command\Doctrine;

use Doctrine\DBAL\Tools\Console\Command\ImportCommand as BaseImportCommand;

/**
 * The `dbal:import` Doctrine command bridge.
 */
class ImportCommand extends BaseImportCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:import');
    }
}
