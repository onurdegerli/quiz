<?php declare(strict_types=1);

namespace Core\Databases\Mysql;

use Core\Databases\Database;
use Core\Exceptions\DatabaseException;
use Exception;
use PDO;

/**
 * Class Mysql
 * @package Core\Databases\Mysql
 */
final class Mysql implements Database
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
                throw new DatabaseException($e->getMessage());
            }
        }

        return self::$db;
    }
}