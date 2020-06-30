<?php declare(strict_types=1);

namespace Core\Databases;

/**
 * Interface Database
 * @package Core\Databases
 */
interface Database
{
    public static function getInstance(string $host, string $database, string $user, string $password);
}