<?php
namespace App\Models;

interface ModelFactoryInterface
{
    /**
     * Create model instance.
     *
     * @param string $model
     * @param \PDO $db
     * @return object
     */
    public static function build(string $model, \PDO $db): object;
}