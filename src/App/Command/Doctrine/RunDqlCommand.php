<?php

namespace App\Command\Doctrine;

use Doctrine\ORM\Tools\Console\Command\RunDqlCommand as BaseRunDqlCommand;

/**
 * The `orm:run-dql` Doctrine command bridge.
 */
class RunDqlCommand extends BaseRunDqlCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:run-dql');
    }
}
