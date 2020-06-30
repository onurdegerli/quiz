<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\AnswerService;
use App\Services\QuizService;
use App\Services\UserService;
use Core\Http\Request;
use Core\Http\Response;
use DI\Container;
use GUMP;

class UserController
{
    private UserService $userService;
    private QuizService $quizService;
    private AnswerService $answerService;

    public function __construct(Container $container)
    {
        $this->userService = $container->get('UserService');
        $this->quizService = $container->get('QuizService');
        $this->answerService = $container->get('AnswerService');
    }

    public function registerAction(): Response
    {
        $quizzes = $this->quizService->getAll();

        return (new Response)
            ->responseHtml(
                'user/register',
                [
                    'quizzes' => $quizzes,
                ]
            );
    }

    public function saveAction(Request $request): Response
    {
        $is_valid = GUMP::is_valid(
            $request->payload,
            [
                'name' => 'required',
                'quiz' => 'required',
            ]
        );

        if (true !== $is_valid) {
            return (new Response)
                ->responseJson(
                    [
                        'is_valid' => false,
                        'messages' => $is_valid,
                    ],
                    400
                );
        }

        $user = $this->userService->save($request->payload['name']);

        return (new Response)
            ->responseJson(
                [
                    'is_valid' => true,
                    'user' => $user,
                ]
            );
    }

    public function resultAction(Request $request, $id, $quizId): Response
    {
        $userId = (int)$id;
        $quizId = (int)$quizId;

        $name = $this->userService->getNameById($userId);
        $correctAnswer = $this->answerService->getUserCorrectAnswersCount($userId, $quizId);
        $totalQuestion = $this->answerService->getUserAnsweredQuestionsCount($userId, $quizId);

        return (new Response)
            ->responseJson(
                [
                    'name' => $name,
                    'total' => $totalQuestion,
                    'correct' => $correctAnswer
                ]
            );
    }
}