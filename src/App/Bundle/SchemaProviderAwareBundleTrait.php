<?php

namespace App\Bundle;

use App\Doctrine\SchemaProvider\SchemaProviderAwareInterface;
use App\Doctrine\SchemaProvider\SchemaProviderInterface;

/**
 * Provides a standard implementation of the SchemaProviderAwareInterface for bundles in which every schema provider
 * aware bundle names its schema provider SchemaProvider and places it in the Doctrine directory.
 *
 * @see SchemaProviderAwareInterface
 */
trait SchemaProviderAwareBundleTrait
{
    /**
     * The implementation of {@see SchemaProviderAwareInterface::getSchemaProvider}.
     *
     * @return SchemaProviderInterface
     *
     * @throws \LogicException If the schema provider doesn't follow the naming conventions or it is missing.
     */
    public function getSchemaProvider(): SchemaProviderInterface
    {
        $reflection = new \ReflectionClass($this);
        $schemaProviderClass = $reflection->getNamespaceName().'\\Doctrine\\SchemaProvider';
        if (!class_exists($schemaProviderClass)) {
            throw new \LogicException(
                'The schema provider of this bundle doesn\'t follow the standard naming conventions or it is missing!'
            );
        }

        return new $schemaProviderClass();
    }
}
