<?php

namespace App\Repositories;

use App\Models\User;
use PDO;

class UserRepository extends BaseRepository
{
    public function __construct(PDO $db)
    {
        $this->table = User::TABLE_NAME;

        parent::__construct($db);
    }

    public function getNameById(int $id): ?string
    {
        $row = $this->getFieldById('name', $id);

        return $row['name'] ?? null;
    }
}