<?php

namespace App\Command\Doctrine;

use Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand;

/**
 * The `orm:clear-cache:query` Doctrine command bridge.
 */
class ClearCacheQueryCommand extends QueryCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:cache:clear-query');
    }
}
