<?php

namespace App\Models;

class Answer
{
    public const TABLE_NAME = 'answers';

    private int $id;

    private int $quizId;

    private int $questionId;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setQuizId(int $quizId): void
    {
        $this->quizId = $quizId;
    }

    public function setQuestionId(int $questionId): void
    {
        $this->questionId = $questionId;
    }
}