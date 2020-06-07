<?php

namespace App\Services;

use App\Repositories\QuizRepository;

class QuizService
{
    private QuizRepository $quizRepository;

    public function __construct(QuizRepository $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    public function getAll(): array
    {
        return $this->quizRepository->getAll();
    }
}