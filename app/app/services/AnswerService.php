<?php

namespace App\Services;

use App\Repositories\AnswerRepository;
use App\Repositories\UserAnswerRepository;

class AnswerService
{
    private AnswerRepository $answerRepository;
    private UserAnswerRepository $userAnswerRepository;

    public function __construct(
        AnswerRepository $answerRepository,
        UserAnswerRepository $userAnswerRepository
    ) {
        $this->answerRepository = $answerRepository;
        $this->userAnswerRepository = $userAnswerRepository;
    }

    public function getUserCorrectAnswersCount(int $userId, int $quizId): int
    {
        return $this->userAnswerRepository->getUserCorrectAnswersCount($userId, $quizId) ?? 0;
    }

    public function getUserAnsweredQuestionsCount(int $userId, int $quizId): int
    {
        return $this->userAnswerRepository->getUserAnsweredQuestionsCount($userId, $quizId) ?? 0;
    }

    public function getByQuestionId(int $questionId): array
    {
        return $this->answerRepository->getAllByRelationId('question_id', $questionId);
    }

    public function checkIfAnswerRelatedToQuizAndQuestion(int $answerId, int $questionId, int $quizId): bool
    {
        return $this->answerRepository->checkIfAnswerRelatedToQuizAndQuestion($answerId, $questionId, $quizId);
    }

    public function save(int $userId, int $quizId, int $questionId, int $answerId): array
    {
        $data = [
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'question_id' => $questionId,
            'answer_id' => $answerId,
            'is_answer_correct' => $this->answerRepository->isCorrect($answerId) ? 1 : 0,
        ];

        return $this->userAnswerRepository->insert($data);
    }
}