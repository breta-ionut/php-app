<?php

namespace App\Command\Doctrine;

use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;

/**
 * The `orm:validate-schema` Doctrine command bridge.
 */
class SchemaValidateCommand extends ValidateSchemaCommand
{
    use EnsureHelpersTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('doctrine:schema:validate');
    }
}
