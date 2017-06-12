<?php

namespace App\Doctrine;

use App\Doctrine\Schema\Schema;
use App\Doctrine\SchemaProvider\PrioritySchemaProviderInterface;
use App\Doctrine\SchemaProvider\SchemaProviderAwareInterface;
use App\Doctrine\SchemaProvider\SchemaProviderInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * The application's database schema manager.
 */
class SchemaManager
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * The database connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * The schema manager constructor.
     *
     * @param KernelInterface $kernel
     * @param Connection      $connection
     */
    public function __construct(KernelInterface $kernel, Connection $connection)
    {
        $this->kernel = $kernel;
        $this->connection = $connection;
    }

    /**
     * Updates the application's database schema. Every bundle can contribute to the application's schema through a
     * schema provider.
     *
     * @param bool $drop If the schema should be dropped first.
     *
     * @return int The number of executed instructions.
     *
     * @throws \Exception Forwards the exceptions caught during the update.
     */
    public function update(bool $drop = false): int
    {
        if ($drop) {
            $this->drop();
        }

        $this->connection->exec(sprintf('USE %s;', $this->connection->getDatabase()));

        // Encapsulate the update process in a transaction.
        $this->connection->beginTransaction();
        try {
            $noExecutedInstructions = $this->doUpdate();
            $this->connection->commit();

            return $noExecutedInstructions;
        } catch (\Exception $exception) {
            $this->connection->rollBack();

            throw $exception;
        }
    }

    /**
     * Drops the application's database schema.
     */
    public function drop(): void
    {
        $this->connection
            ->getSchemaManager()
            ->dropAndCreateDatabase($this->connection->getDatabase());
    }

    /**
     * The concrete implementation of the update process.
     *
     * @return int The number of executed instructions.
     */
    private function doUpdate(): int
    {
        $manager = $this->connection->getSchemaManager();
        $platform = $this->connection->getDatabasePlatform();

        // Invoke the schema providers.
        $schemaProviders = $this->getSchemaProviders();
        $noExecutedInstructions = 0;
        foreach ($schemaProviders as $schemaProvider) {
            $schema = Schema::fromBaseSchema($manager->createSchema());
            $alteredSchema = $schemaProvider->getSchema(clone $schema);

            // Compute the differences generated by the current schema provider and execute them.
            $diff = $schema->getMigrateToSql($alteredSchema, $platform);
            foreach ($diff as $sql) {
                $this->connection->exec($sql);
                $noExecutedInstructions++;
            }
        }

        return $noExecutedInstructions;
    }

    /**
     * Gets the schema providers defined in the application ordered by their execution priority.
     *
     * @return SchemaProviderInterface[]
     */
    private function getSchemaProviders(): array
    {
        $schemaProviders = [];
        foreach ($this->kernel->getBundles() as $bundle) {
            if (!$bundle instanceof SchemaProviderAwareInterface) {
                continue;
            }

            $schemaProvider = $bundle->getSchemaProvider();
            // Check if the provider demanded a custom priority.
            $priority = $schemaProvider instanceof PrioritySchemaProviderInterface ? $schemaProvider->getPriority() : 0;

            $schemaProviders[$priority][] = $schemaProvider;
        }

        // Shorten the process if no schema providers were defined.
        if (!$schemaProviders) {
            return [];
        }

        // Sort the providers by priority.
        krsort($schemaProviders);

        return array_merge(...$schemaProviders);
    }
}
