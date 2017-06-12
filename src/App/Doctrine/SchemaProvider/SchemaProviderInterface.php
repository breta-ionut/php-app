<?php

namespace App\Doctrine\SchemaProvider;

use App\Doctrine\Schema\Schema;

/**
 * A contract which all schema providers must implement. A schema provider provides the database schema specific to a
 * component(bundle).
 */
interface SchemaProviderInterface
{
    /**
     * Provides the component's database schema.
     *
     * @param Schema $schema The application's already existing schema.
     *
     * @return Schema
     */
    public function getSchema(Schema $schema): Schema;
}
