<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\Question;
use PDO;

class QuestionRepository extends BaseRepository
{
    public function __construct(PDO $db)
    {
        $this->table = Question::TABLE_NAME;

        parent::__construct($db);
    }
}