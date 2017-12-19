<?php

namespace App\Command\Doctrine;

use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;

/**
 * The `orm:schema-tool:update` Doctrine command bridge.
 */
class SchemaUpdateCommand extends UpdateCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:schema:update');
    }
}
