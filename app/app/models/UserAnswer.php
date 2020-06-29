<?php declare(strict_types=1);

namespace App\Models;

class UserAnswer
{
    public const TABLE_NAME = 'user_answers';

    private int $userId;

    private int $quizId;

    private int $questionId;

    private int $answerId;

    private int $questionSize = 0;

    private int $corrects = 0;

    private Answer $answer;

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setQuizId(int $quizId): void
    {
        $this->quizId = $quizId;
    }

    public function setQuestionId(int $questionId): void
    {
        $this->questionId = $questionId;
    }

    public function setAnswerId(int $answerId): void
    {
        $this->answerId = $answerId;
    }

    public function setAnswer(Answer $answer): void
    {
        $this->answer = $answer;
    }

    public function getCorrects(): int
    {
        return $this->corrects;
    }

    public function getQuestionSize(): int
    {
        return count($this->questionsAnswers);
    }
}