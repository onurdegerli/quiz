<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\AnswerService;
use App\Services\QuizService;
use App\Services\UserService;
use DI\Container;
use GUMP;

class UserController extends Controller
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

    public function registerAction(): void
    {
        $quizzes = $this->quizService->getAll();

        $this->view(
            'user/register',
            [
                'quizzes' => $quizzes,
            ]
        );
    }

    public function saveAction(array $request): void
    {
        $is_valid = GUMP::is_valid(
            $request['payload'],
            [
                'name' => 'required',
                'quiz' => 'required',
            ]
        );

        if (true === $is_valid) {
            $user = $this->userService->save($request['payload']['name']);

            $this->viewJson(
                [
                    'is_valid' => true,
                    'user' => $user,
                ]
            );
        } else {
            $this->viewJson(
                [
                    'is_valid' => false,
                    'messages' => $is_valid,
                ]
            );
        }
    }

    public function resultAction(array $request, array $slugs): void
    {
        $userId = (int)$slugs['id'];
        $quizId = (int)$slugs['quiz_id'];

        $name = $this->userService->getNameById($userId);
        $correctAnswer = $this->answerService->getUserCorrectAnswersCount($userId, $quizId);
        $totalQuestion = $this->answerService->getUserAnsweredQuestionsCount($userId, $quizId);

        $this->viewJson(
            [
                'name' => $name,
                'total' => $totalQuestion,
                'correct' => $correctAnswer
            ]
        );
    }
}