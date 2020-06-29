<?php

namespace Core\Databases;

abstract class Connection
{
    abstract public function get(): Database;
}