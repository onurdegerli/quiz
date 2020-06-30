<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\AnswerService;
use App\Services\QuestionService;
use Core\Exceptions\ResponseException;
use Core\Http\Request;
use Core\Http\Response;
use DI\Container;
use GUMP;

class QuizController
{
    private QuestionService $questionService;
    private AnswerService $answerService;

    public function __construct(Container $container)
    {
        $this->questionService = $container->get('QuestionService');
        $this->answerService = $container->get('AnswerService');
    }

    public function questionAction(Request $request, $quizId): Response
    {
        $quizId = (int)$quizId;

        if (!empty($request->get['current'])) {
            $questionId = (int)$request->get['current'];

            $question = $this->questionService
                ->getFirstQuestionByQuizIdGreaterThan($questionId, $quizId);
            $progressRate = $this->questionService->getProgressRate($questionId, $quizId);
        } else {
            $question = $this->questionService->getFirstQuestionByQuizId($quizId);
            $progressRate = 0;
        }

        if (!$question) {
            throw new ResponseException('Question not found.');
        }

        if (!empty($question)) {
            $question['answers'] = $this->answerService->getByQuestionId($question['id']);
        }

        return (new Response)
            ->responseJson(
                [
                    'question' => $question,
                    'progressRate' => $progressRate,
                ]
            );
    }

    public function answerAction(Request $request): Response
    {
        $isValid = GUMP::is_valid(
            $request->payload,
            [
                'user' => 'required|numeric',
                'quiz' => 'required|numeric',
                'question' => 'required|numeric',
                'answer' => 'required|numeric',
            ]
        );

        if (true !== $isValid) {
            return (new Response)
                ->responseJson(
                    [
                        'is_valid' => false,
                        'messages' => $isValid,
                    ],
                    400
                );
        }

        $userId = (int)$request->payload['user'];
        $quizId = (int)$request->payload['quiz'];
        $questionId = (int)$request->payload['question'];
        $answerId = (int)$request->payload['answer'];

        $isRelated = $this->answerService
            ->checkIfAnswerRelatedToQuizAndQuestion($answerId, $questionId, $quizId);

        if (true !== $isRelated) {
            return (new Response)
                ->responseJson(
                    [
                        'is_valid' => false,
                        'messages' => 'The answer does not belong to the question.',
                    ],
                    400
                );
        }

        return (new Response)
            ->responseJson(
                [
                    'is_valid' => true,
                    'data' => $this->answerService
                        ->save(
                            $userId,
                            $quizId,
                            $questionId,
                            $answerId
                        )
                ]
            );
    }
}