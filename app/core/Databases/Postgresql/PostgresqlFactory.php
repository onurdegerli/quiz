<?php

namespace Core\Databases\Postgresql;

use Core\Databases\Connection;

class PostgresqlFactory extends Connection
{
    public function get(): Postgresql
    {
        return new Postgresql();
    }
}