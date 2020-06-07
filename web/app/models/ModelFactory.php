<?php

namespace App\Models;

use App\Models\ModelFactoryInterface;

class ModelFactory implements ModelFactoryInterface
{
    /**
     * Create model instance.
     *
     * @param string $model
     * @param \PDO $db
     * @return object
     */
    public static function build(string $model, \PDO $db): object
    {
        $modelName = '\\App\Models\\' . ucwords($model);
        return new $modelName($db);
    }
}