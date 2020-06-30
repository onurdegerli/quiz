<?php

namespace Core\Databases\Mysql;

use Core\Databases\Connection;

/**
 * Class MysqlFactory
 * @package Core\Databases\Mysql
 */
class MysqlFactory extends Connection
{
    public function get(): Mysql
    {
        return new Mysql();
    }
}