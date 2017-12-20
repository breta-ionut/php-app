<?php

namespace App\Command\Doctrine;

use Doctrine\DBAL\Tools\Console\Command\RunSqlCommand as BaseRunSqlCommand;

/**
 * The `dbal:run-sql` Doctrine command bridge.
 */
class RunSqlCommand extends BaseRunSqlCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:run-sql');
    }
}
