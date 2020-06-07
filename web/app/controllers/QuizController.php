<?php
namespace App\Controllers;

use App\Models\ModelFactory;
use GUMP;

class QuizController extends Controller
{
    /**
     * Question model.
     *
     * @var \App\Models\Question
     */
    private $question;

    /**
     * Answer model.
     *
     * @var \App\Models\Answer
     */
    private $answer;

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

        $this->question = ModelFactory::build('question', $container->get('db'));
        $this->answer = ModelFactory::build('answer', $container->get('db'));
        $this->userAnswer = ModelFactory::build('userAnswer', $container->get('db'));
    }

    /**
     * Get question by quiz.
     *
     * @param array $request
     * @param array $slugs
     * @return void
     */
    public function questionAction(array $request, array $slugs): void
    {
        $quizId = $slugs['id'];
        $question = [];
        $this->question->setQuizId($quizId);

        if (!empty($request['get']['current'])) {
            $this->question->setId($request['get']['current']);
            $question = $this->question->getFirstQuestionByQuizIdGreaterThan();

            $totalAnswers = $this->question->countQuestionByQuizId();
            $remainingQuestionCount = $this->question->countRemainingQuestionByQuizId();
            $answeredQuestionCount = $totalAnswers - $remainingQuestionCount;
            $progressRate = round((($answeredQuestionCount * 100) / $totalAnswers), 0);
        } else {
            $question = $this->question->getFirstQuestionByQuizId();
            $progressRate = 0;
        }

        if (!empty($question)) {
            $this->answer->setQuestionId($question['id']);
            $question['answers'] = $this->answer->getByQuestionId();
        }

        $this->viewJson([
            'question' => $question,
            'progressRate' => $progressRate,
        ]);
    }

    /**
     * Store answer given by user.
     *
     * @param array $request
     * @return void
     */
    public function answerAction(array $request): void
    {
        $isValid = GUMP::is_valid($request['payload'], array(
            'user' => 'required|numeric',
            'quiz' => 'required|numeric',
            'question' => 'required|numeric',
            'answer' => 'required|numeric',
        ));

        $response = [];
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

            $this->answer->setId($answerId);
            $this->answer->setQuestionId($questionId);
            $this->answer->setQuizId($quizId);
            $isRelated = $this->answer->checkIfAnswerRelatedToQuizAndQuestion();
    
            if (TRUE === $isRelated) {
                $this->userAnswer->setUserId((int)$userId);
                $this->userAnswer->setQuizId($quizId);
                $this->userAnswer->setQuestionId($questionId);
                $this->userAnswer->setAnswerId($answerId);
                $this->userAnswer->setAnswer($this->answer);
    
                $response = [
                    'is_valid' => true,
                    'data' => $this->userAnswer->save()
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