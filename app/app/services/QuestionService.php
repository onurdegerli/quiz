<?php declare(strict_types=1);

namespace App\Services;

use App\Exceptions\QuestionException;
use App\Repositories\QuestionRepository;

class QuestionService
{
    private QuestionRepository $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * @param int $quizId
     * @param int|null $questionId
     * @return array|null
     */
    public function getQuestion(int $quizId, ?int $questionId)
    {
        if ($questionId) {
            return $this->getFirstQuestionByQuizIdGreaterThan($questionId, $quizId);
        }

        return $this->getFirstQuestionByQuizId($quizId);
    }

    public function getProgressRate(int $quizId, ?int $questionId): float
    {
        if (!$questionId) {
            return 0;
        }

        $totalAnswers = $this->countQuestionByQuizId($quizId);
        $remainingQuestionCount = $this->countRemainingQuestionByQuizId($questionId, $quizId);
        $answeredQuestionCount = $totalAnswers - $remainingQuestionCount;

        return round((($answeredQuestionCount * 100) / $totalAnswers), 0);
    }

    public function getFirstQuestionByQuizId(int $quizId): ?array
    {
        return $this->questionRepository
            ->getByRelationId('quiz_id', $quizId) ?: null;
    }

    public function getFirstQuestionByQuizIdGreaterThan(int $id, int $quizId): ?array
    {
        return $this->questionRepository
            ->getByRelationIdGreaterThan('quiz_id', $quizId, $id) ?: null;
    }

    public function countQuestionByQuizId(int $quizId): int
    {
        return $this->questionRepository
            ->countByRelationId('quiz_id', $quizId);
    }

    public function countRemainingQuestionByQuizId(int $id, int $quizId): int
    {
        return $this->questionRepository
            ->countByRelationIdGreaterThan('quiz_id', $quizId, $id);
    }
}