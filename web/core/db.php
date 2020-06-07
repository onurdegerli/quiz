<?php

namespace Core;

use PDO;

/**
 * Database connection class
 */
abstract class Db
{
    static $db = null;

    public static function getInstance(string $host, string $database, string $user, string $password)
    {
        if (null === self::$db) {
            $dsn = 'mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8';
            $options = [
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                self::$db = new PDO($dsn, $user, $password, $options);
            } catch (Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }

        return self::$db;
    }
}