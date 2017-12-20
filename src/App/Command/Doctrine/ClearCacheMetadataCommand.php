<?php

namespace App\Command\Doctrine;

use Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand;

/**
 * The `orm:clear-cache:metadata` Doctrine command bridge.
 */
class ClearCacheMetadataCommand extends MetadataCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:cache:clear-metadata');
    }
}
