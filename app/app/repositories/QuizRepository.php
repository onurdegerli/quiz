<?php

namespace App\Repositories;

use App\Models\Quiz;
use PDO;

class QuizRepository extends BaseRepository
{
    public function __construct(PDO $db)
    {
        $this->table = Quiz::TABLE_NAME;

        parent::__construct($db);
    }

    public function save(User $user): User
    {
        return $this->insert($user);
    }
}