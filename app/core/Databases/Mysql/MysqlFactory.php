<?php

namespace Core\Databases\Mysql;

use Core\Databases\Connection;

class MysqlFactory extends Connection
{
    public function get(): Mysql
    {
        return new Mysql();
    }
}