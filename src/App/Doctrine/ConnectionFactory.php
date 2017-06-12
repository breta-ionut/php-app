<?php

namespace App\Doctrine;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

/**
 * Doctrine database connections factory.
 */
class ConnectionFactory
{
    /**
     * Creates a Doctrine database connection.
     *
     * @param string $driver
     * @param string $host
     * @param int    $port
     * @param string $user
     * @param string $password
     * @param string $database
     *
     * @return Connection
     */
    public static function factory(
        string $driver,
        string $host,
        int $port,
        string $user,
        string $password,
        string $database
    ): Connection {
        $params = [
            'driver' => $driver,
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'password' => $password,
            'dbname' => $database,
        ];
        $config = new Configuration();

        return DriverManager::getConnection($params, $config);
    }
}
