<?php

namespace App\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;

/**
 * The Doctrine entity manager factory.
 */
class EntityManagerFactory
{
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
        string $env
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
        $config = Setup::createConfiguration($isDev);

        return EntityManager::create($connParams, $config);
    }
}
