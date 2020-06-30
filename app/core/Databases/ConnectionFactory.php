<?php

namespace Core\Databases;

use Core\Databases\Mysql\MysqlFactory;
use Core\Databases\Postgresql\PostgresqlFactory;
use Core\Exceptions\DatabaseException;

/**
 * Class ConnectionFactory
 * @package Core\Databases
 */
class ConnectionFactory
{
    public static function get(string $type)
    {
        if ($type === 'mysql') {
            return (new MysqlFactory())->get();
        }

        if ($type === 'postgresql') {
            return (new PostgresqlFactory())->get();
        }

        throw new DatabaseException('No database type specified.');
    }
}