<?php

namespace App\Command\Doctrine;

use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;

/**
 * The `orm:schema-tool:create` Doctrine command bridge.
 */
class SchemaCreateCommand extends CreateCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:schema:create');
    }
}
