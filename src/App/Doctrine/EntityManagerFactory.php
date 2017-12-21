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
    private const MAPPING_FILES_EXTENSION = '.yaml';

    /**
     * Creates an entity manager given the database connection parameters and some application details.
     *
     * @param array  $connectionParams The database connection parameters (driver, host, etc.).
     * @param string $env              The application environment (dev, prod).
     * @param array  $bundles          The application bundles metadata.
     *
     * @return EntityManagerInterface
     */
    public static function factory(array $connectionParams, string $env, array $bundles): EntityManagerInterface
    {
        $isDev = in_array($env, ['dev', 'test'], true);

        // Determine the paths where the mapping files should be located.
        $mappingPaths = [];
        foreach ($bundles as $bundle) {
            if (!is_dir($bundleMappingPath = $bundle['path'].'/'.self::MAPPING_BUNDLE_PATH)) {
                continue;
            }

            $mappingPaths[$bundleMappingPath] = $bundle['namespace'].'\\'.self::MAPPING_BUNDLE_NAMESPACE;
        }

        $config = Setup::createConfiguration($isDev);
        $config->setMetadataDriverImpl(new YamlDriver(new SymfonyFileLocator(
            $mappingPaths,
            self::MAPPING_FILES_EXTENSION
        )));

        return EntityManager::create($connectionParams, $config);
    }
}
