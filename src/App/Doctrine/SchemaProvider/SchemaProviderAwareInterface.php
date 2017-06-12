<?php

namespace App\Doctrine\SchemaProvider;

/**
 * All components which have a schema provider defining their database schema must implement this contract in order to
 * let the schema manager access the provider. This interface will be mainly implemented by bundles which offer a
 * database structure.
 */
interface SchemaProviderAwareInterface
{
    /**
     * Returns the schema provider.
     *
     * @return SchemaProviderInterface
     */
    public function getSchemaProvider(): SchemaProviderInterface;
}
