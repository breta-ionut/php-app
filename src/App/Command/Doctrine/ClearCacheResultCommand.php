<?php

namespace App\Command\Doctrine;

use Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand;

/**
 * The `orm:clear-cache:result` Doctrine command bridge.
 */
class ClearCacheResultCommand extends ResultCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:cache:clear-result');
    }
}
