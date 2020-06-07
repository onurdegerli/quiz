<?php

namespace App\Controllers;

use App\Models\ModelFactory;
use App\Services\AnswerService;
use App\Services\QuestionService;
use DI\Container;
use GUMP;

class QuizController extends Controller
{
    private QuestionService $questionService;

    private AnswerService $answerService;

    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->questionService = $container->get('QuestionService');
        $this->answerService = $container->get('AnswerService');
    }

    public function questionAction(array $request, array $slugs): void
    {
        $quizId = $slugs['id'];

        if (!empty($request['get']['current'])) {
            $questionId = $request['get']['current'];

            $question = $this->questionService
                ->getFirstQuestionByQuizIdGreaterThan($questionId, $quizId);
            $progressRate = $this->questionService->getProgressRate($questionId, $quizId);
        } else {
            $question = $this->questionService->getFirstQuestionByQuizId($quizId);
            $progressRate = 0;
        }

        if (!empty($question)) {
            $question['answers'] = $this->answerService->getByQuestionId($question['id']);
        }

        $this->viewJson(
            [
                'question' => $question,
                'progressRate' => $progressRate,
            ]
        );
    }

    public function answerAction(array $request): void
    {
        $isValid = GUMP::is_valid(
            $request['payload'],
            array(
                'user' => 'required|numeric',
                'quiz' => 'required|numeric',
                'question' => 'required|numeric',
                'answer' => 'required|numeric',
            )
        );

        if (true !== $isValid) {
            $response = [
                'is_valid' => false,
                'messages' => $isValid,
            ];
        } else {
            $userId = $request['payload']['user'];
            $quizId = $request['payload']['quiz'];
            $questionId = $request['payload']['question'];
            $answerId = $request['payload']['answer'];

            $isRelated = $this->answerService
                ->checkIfAnswerRelatedToQuizAndQuestion($answerId, $questionId, $quizId);

            if (true === $isRelated) {
                $response = [
                    'is_valid' => true,
                    'data' => $this->answerService
                        ->save(
                            $userId,
                            $quizId,
                            $questionId,
                            $answerId
                        )
                ];
            } else {
                $response = [
                    'is_valid' => false,
                    'messages' => 'The answer does not belong to the question.',
                ];
            }
        }

        $this->viewJson($response);
    }
}