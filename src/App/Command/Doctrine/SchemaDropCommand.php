<?php

namespace App\Command\Doctrine;

use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;

/**
 * The `orm:schema-tool:drop` Doctrine command bridge.
 */
class SchemaDropCommand extends DropCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:schema:drop');
    }
}
