<?php

namespace App\Command\Doctrine;

use Doctrine\ORM\Tools\Console\Command\InfoCommand;

/**
 * The `orm:info` Doctrine command bridge.
 */
class MappingInfoCommand extends InfoCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:mapping:info');
    }
}
