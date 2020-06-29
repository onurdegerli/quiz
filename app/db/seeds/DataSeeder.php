<?php


use Phinx\Seed\AbstractSeed;

class DataSeeder extends AbstractSeed
{

    const QUIZ_COUNT = 20;
    const QUESTION_LIMIT = 20;
    const ANSWER_LIMIT = 11;

    private $userTable;
    private $quizTable;
    private $questionTable;
    private $answerTable;
    private $userAnswerTable;

    private $answerOptions = [
        'colorName',
        'randomNumber',
        'jobTitle',
        'fileExtension',
        'name',
        'phoneNumber',
        'word',
        'state',
        'city',
        'streetName',
        'country'
    ];

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $this->set_tables();
        $this->truncateTables();

        $faker = Faker\Factory::create();

        $this->createQuizData($faker);
        $this->createQuestionData($faker);
        $this->createAnswerData($faker);
    }

    /**
     * Sets table instances.
     *
     * @return void
     */
    private function set_tables(): void
    {
        $this->userTable = $this->table('users');
        $this->quizTable = $this->table('quizzes');
        $this->questionTable = $this->table('questions');
        $this->answerTable = $this->table('answers');
        $this->userAnswerTable = $this->table('user_answers');
    }

    /**
     * Truncates table before storing data.
     *
     * @return void
     */
    private function truncateTables(): void
    {
        $this->execute('SET foreign_key_checks=0');

        $this->userTable->truncate();
        $this->quizTable->truncate();
        $this->questionTable->truncate();
        $this->answerTable->truncate();
        $this->userAnswerTable->truncate();
    }

    /**
     * Creates quiz table data.
     *
     * @param \Faker\Generator $faker
     * @return void
     */
    private function createQuizData(\Faker\Generator $faker): void
    {
        for ($i = 1; $i <= self::QUIZ_COUNT; $i++) {
            $quizName = 'Quiz ' . $i;
            $this->quizTable->insert(['name' => $quizName])->save();
        }
    }

    /**
     * Creates question table data.
     *
     * @param \Faker\Generator $faker
     * @return void
     */
    private function createQuestionData(\Faker\Generator $faker): void
    {
        $quizList = $this->fetchAll('SELECT * FROM quizzes');
        foreach ($quizList as $quizRow) {
            $questionCount = self::getRandomNumber(self::QUESTION_LIMIT);
            for ($i = 1; $i <= $questionCount; $i++) {
                $questionContent = rtrim($faker->sentence(6, true), '.') . '?';
                $questionData = [
                    'quiz_id' => $quizRow['id'],
                    'question' => $questionContent
                ];
                $this->questionTable->insert($questionData)->save();
            }
        }
    }

    /**
     * Creates answer table data.
     *
     * @param \Faker\Generator $faker
     * @return void
     */
    private function createAnswerData(\Faker\Generator $faker): void
    {
        $questionList = $this->fetchAll('SELECT * FROM questions');
        foreach ($questionList as $questionRow) {
            $answerCount = self::getRandomNumber(self::ANSWER_LIMIT, 2);
            $randomAnswerOption = self::getRandomNumber(count($this->answerOptions) -1);
            $answerOption = $this->answerOptions[$randomAnswerOption];
            $correctAnswerIndex = self::getRandomNumber($answerCount);

            for ($i = 1; $i <= $answerCount; $i++) {
                $answerContent = $faker->$answerOption();
                $answerData = [
                    'question_id' => $questionRow['id'],
                    'answer' => $answerContent,
                    'is_correct' => $i == $correctAnswerIndex
                ];
                $this->answerTable->insert($answerData)->save();
            }
        }
    }

    /**
     * Generates a random integer number.
     *
     * @param integer $limit
     * @param integer $start
     * @return integer
     */
    private static function getRandomNumber(int $limit, int $start = 1): int
    {
        return rand($start, $limit);
    }
}
