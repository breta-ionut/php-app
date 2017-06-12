<?php

namespace App\Doctrine\SchemaProvider;

/**
 * Contract which should be implemented by schema providers wanting a specific execution priority. Schema providers with
 * higher priorities get called first.
 */
interface PrioritySchemaProviderInterface extends SchemaProviderInterface
{
    /**
     * Returns the demanded execution priority.
     *
     * @return int
     */
    public function getPriority(): int;
}
