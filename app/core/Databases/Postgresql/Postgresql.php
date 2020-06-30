<?php declare(strict_types=1);

namespace Core\Databases\Postgresql;

use Core\Databases\Database;
use Core\Exceptions\DatabaseException;

/**
 * Class Postgresql
 * @package Core\Databases\Postgresql
 */
final class Postgresql implements Database
{
    static $db = null;

    public static function getInstance(string $host, string $database, string $user, string $password)
    {
        if (null === self::$db) {
            throw new DatabaseException('Provide PostgreSQL connection.');
        }

        return self::$db;
    }
}