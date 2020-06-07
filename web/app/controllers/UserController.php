<?php
namespace App\Controllers;

use App\Models\ModelFactory;
use GUMP;

class UserController extends Controller
{
    /**
     * User model.
     *
     * @var \App\Models\User
     */
    private $user;

    /**
     * Quiz model.
     *
     * @var \App\Models\Quiz
     */
    private $quiz;

    /**
     * UserAnswer model.
     *
     * @var \App\Models\UserAnswer
     */
    private $userAnswer;

    /**
     * Constructor
     *
     * @param \DI\Container $container
     */
    public function __construct(\DI\Container $container)
    {
        parent::__construct($container);

        $this->user = ModelFactory::build('user', $container->get('db'));
        $this->quiz = ModelFactory::build('quiz', $container->get('db'));
        $this->userAnswer = ModelFactory::build('userAnswer', $container->get('db'));
    }

    /**
     * Register view action.
     *
     * @return void
     */
    public function registerAction(): void
    {
        $quizzes = $this->quiz->getAll();

        $this->view('user/register', [
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * Stores user data which is given by user.
     *
     * @param array $request
     * @return void
     */
    public function saveAction(array $request): void
    {
        $is_valid = GUMP::is_valid($request['payload'], array(
            'name' => 'required',
            'quiz' => 'required',
        ));

        $response = [];
        if (true === $is_valid) {
            $this->user->setName($request['payload']['name']);
            $user = $this->user->save();

            $response = [
                'is_valid' => true,
                'user' => $user,
            ];
        } else {
            $response = [
                'is_valid' => false,
                'messages' => $is_valid,
            ];
        }

        $this->viewJson($response);
    }

    /**
     * Shows the user results.
     *
     * @param array $request
     * @param array $slugs
     * @return void
     */
    public function resultAction(array $request, array $slugs): void
    {
        $userId = $slugs['id'];
        $quizId = $slugs['quiz_id'];

        $this->user->setId($userId);
        $name = $this->user->getNameById();

        $this->userAnswer->setUserId($userId);
        $this->userAnswer->setQuizId($quizId);
        $correctAnswer = $this->userAnswer->countUserCorrectAnswers();
        $totalQuestion = $this->userAnswer->countUserAnsweredQuestions();

        $this->viewJson([
            'name' => $name,
            'total' => $totalQuestion,
            'correct' => $correctAnswer
        ]);
    }
}