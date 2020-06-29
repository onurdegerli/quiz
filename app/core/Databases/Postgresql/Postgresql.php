<?php declare(strict_types=1);

namespace Core\Databases\Postgresql;

use Core\Databases\Database;

final class Postgresql implements Database
{
    static $db = null;

    public static function getInstance(string $host, string $database, string $user, string $password)
    {
        if (null === self::$db) {
            // Make PostgreSQL connection
        }

        return self::$db;
    }
}