<?php

namespace App\Repositories;

use App\Models\UserAnswer;
use PDO;

class UserAnswerRepository extends BaseRepository
{
    public function __construct(PDO $db)
    {
        $this->table = UserAnswer::TABLE_NAME;

        parent::__construct($db);
    }

    public function getUserCorrectAnswersCount(int $userId, int $quizId): ?int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(1) FROM $this->table WHERE user_id = ? AND quiz_id = ? AND is_answer_correct = ?"
        );
        $stmt->execute(
            [
                $userId,
                $quizId,
                1,
            ]
        );

        return $stmt->fetch($this->db::FETCH_COLUMN);
    }

    public function getUserAnsweredQuestionsCount(int $userId, int $quizId): ?int
    {
        $stmt = $this->db->prepare("SELECT COUNT(1) FROM $this->table WHERE user_id = ? AND quiz_id = ?");
        $stmt->execute(
            [
                $userId,
                $quizId,
            ]
        );

        return $stmt->fetch($this->db::FETCH_COLUMN);
    }
}