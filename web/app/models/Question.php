<?php
namespace App\Models;

class Question
{
    public const TABLE_NAME = 'questions';

    private int $id;

    private int $quizId;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setQuizId(int $quizId)
    {
        $this->quizId = $quizId;
    }
}