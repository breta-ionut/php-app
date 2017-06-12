<?php

namespace Bundles\Main\Doctrine;

use App\Doctrine\Schema\Schema;
use App\Doctrine\SchemaProvider\SchemaProviderInterface;

/**
 * The bundle's schema provider.
 */
class SchemaProvider implements SchemaProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSchema(Schema $schema): Schema
    {
        $table = $schema->createTable('user');

        $table->addColumn('id', 'integer', ['auto_increment' => true]);
        $table->addColumn('name', 'string', ['length' => 1024]);

        return $schema;
    }
}
