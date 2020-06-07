<?php
namespace App\Models;

class User
{
    public const TABLE_NAME = 'users';

    private int $id;

    private string $name;

    private int $quizId;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setQuizId(int $quizId): void
    {
        $this->quizId = $quizId;
    }
}