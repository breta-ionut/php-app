<?php

namespace App\Doctrine\Schema;

use Doctrine\DBAL\Schema\Schema as BaseSchema;

/**
 * An enhanced version of the Doctrine schema representation.
 */
class Schema extends BaseSchema
{
    /**
     * Creates a new instance from a BaseSchema instance.
     *
     * @param BaseSchema $schema
     *
     * @return static
     */
    public static function fromBaseSchema(BaseSchema $schema): self
    {
        return new static($schema->_tables, $schema->_sequences, $schema->_schemaConfig, $schema->getNamespaces());
    }

    /**
     * {@inheritdoc}
     *
     * @param bool $dropIfExists Whether to drop or not an already existing table with the same name.
     */
    public function createTable($tableName, bool $dropIfExists = true)
    {
        if ($dropIfExists && $this->hasTable($tableName)) {
            $this->dropTable($tableName);
        }

        return parent::createTable($tableName);
    }

    /**
     * {@inheritdoc}
     *
     * @param bool $dropIfExists Whether to drop or not an already existing sequence with the same name.
     */
    public function createSequence($sequenceName, $allocationSize = 1, $initialValue = 1, bool $dropIfExists = true)
    {
        if ($dropIfExists && $this->hasSequence($sequenceName)) {
            $this->dropSequence($sequenceName);
        }

        return parent::createSequence($sequenceName, $allocationSize, $initialValue);
    }
}
