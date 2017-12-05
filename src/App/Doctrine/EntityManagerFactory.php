<?php

namespace App\Doctrine;

use Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Doctrine\ORM\Tools\Setup;

/**
 * The Doctrine entity manager factory.
 */
class EntityManagerFactory
{
    private const MAPPING_BUNDLE_PATH = 'Resources/config/doctrine';
    private const MAPPING_BUNDLE_NAMESPACE = 'Model';
    private const MAPPING_FILES_EXTENSION = 'yaml';

    /**
     * Creates an entity manager given the database connection and some application details.
     *
     * @param string $driver   The database driver (e.g. pdo_mysql).
     * @param string $host
     * @param int    $port
     * @param string $user
     * @param string $password
     * @param string $database
     * @param string $env      The application environment (dev, prod).
     * @param array  $bundles  The application bundles metadata.
     *
     * @return EntityManagerInterface
     */
    public static function factory(
        string $driver,
        string $host,
        int $port,
        string $user,
        string $password,
        string $database,
        string $env,
        array $bundles
    ): EntityManagerInterface {
        $connParams = [
            'driver' => $driver,
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'password' => $password,
            'dbname' => $database,
        ];

        $isDev = in_array($env, ['dev', 'test'], true);

        // Determine the paths where the mapping files should be located.
        $mappingPaths = [];
        foreach ($bundles as $bundle) {
            $bundleMappingPath = $bundle['path'].'/'.self::MAPPING_BUNDLE_PATH;
            $bundleMappingNamespace = $bundle['namespace'].'\\'.self::MAPPING_BUNDLE_NAMESPACE;

            $mappingPaths[$bundleMappingPath] = $bundleMappingNamespace;
        }

        $config = Setup::createConfiguration($isDev);
        $config->setMetadataDriverImpl(new YamlDriver(new SymfonyFileLocator(
            $mappingPaths,
            self::MAPPING_FILES_EXTENSION
        )));

        return EntityManager::create($connParams, $config);
    }
}
