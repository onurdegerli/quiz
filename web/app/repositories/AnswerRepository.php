<?php

namespace App\Repositories;

use App\Models\Answer;
use PDO;

class AnswerRepository extends BaseRepository
{
    public function __construct(PDO $db)
    {
        $this->table = Answer::TABLE_NAME;

        parent::__construct($db);
    }

    public function checkIfAnswerRelatedToQuizAndQuestion(int $answerId, int $questionId, int $quizId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(1) 
            FROM $this->table a
            LEFT JOIN questions qe
                ON a.question_id = qe.id
            LEFT JOIN quizzes qi
                ON qe.quiz_id = qi.id
            WHERE a.id = :answer_id 
                AND qe.id = :question_id
                AND qi.id = :quiz_id"
        );

        $stmt->bindValue(':answer_id', $answerId);
        $stmt->bindValue(':question_id', $questionId);
        $stmt->bindValue(':quiz_id', $quizId);
        $stmt->execute();
        $count = $stmt->fetch($this->db::FETCH_COLUMN);

        return $count > 0;
    }

    public function isCorrect(int $answerId): int
    {
        $row = $this->get($answerId);

        return $row['is_correct'];
    }
}