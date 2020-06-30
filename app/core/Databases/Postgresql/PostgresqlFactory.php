<?php

namespace Core\Databases\Postgresql;

use Core\Databases\Connection;

/**
 * Class PostgresqlFactory
 * @package Core\Databases\Postgresql
 */
class PostgresqlFactory extends Connection
{
    public function get(): Postgresql
    {
        return new Postgresql();
    }
}