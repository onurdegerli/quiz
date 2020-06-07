<?php
namespace App\Models;

use App\Models\Model;
use App\Models\Answer;

class UserAnswer extends Model
{
    /**
     * Model table.
     *
     * @var string
     */
    protected $table = 'user_answers';

    /**
     * User id.
     *
     * @var integer
     */
    private $userId;

    /**
     * Quiz id.
     *
     * @var integer
     */
    private $quizId;

    /**
     * Question id.
     *
     * @var integer
     */
    private $questionId;

    /**
     * Answer model.
     *
     * @var \App\Models\Answer
     */
    private $answer;

    /**
     * Question size.
     *
     * @var integer
     */
    private $questionSize = 0;

    /**
     * Correct size.
     *
     * @var integer
     */
    private $corrects = 0;

    /**
     * Sets user id.
     *
     * @param integer $userId
     * @return void
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * Sets quiz id.
     *
     * @param integer $quizId
     * @return void
     */
    public function setQuizId(int $quizId): void
    {
        $this->quizId = $quizId;
    }

    /**
     * Sets question id.
     *
     * @param integer $questionId
     * @return void
     */
    public function setQuestionId(int $questionId): void
    {
        $this->questionId = $questionId;
    }

    /**
     * Sets answer id.
     *
     * @param integer $answerId
     * @return void
     */
    public function setAnswerId(int $answerId): void
    {
        $this->answerId = $answerId;
    }

    /**
     * Sets answer model.
     *
     * @param \App\Models\Answer $answer
     * @return void
     */
    public function setAnswer(\App\Models\Answer $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * Gets correct answers.
     *
     * @return integer
     */
    public function getCorrects(): int
    {
        return $this->corrects;
    }

    /**
     * Counts question's answers.
     *
     * @return integer
     */
    public function getQuestionSize(): int
    {
        return count($this->questionsAnswers);
    }

    /**
     * Saves user answers.
     *
     * @return array
     */
    public function save(): array
    {
        $data = [
            'user_id' => $this->userId,
            'quiz_id' => $this->quizId,
            'question_id' => $this->questionId,
            'answer_id' => $this->answerId,
            'is_answer_correct' => $this->answer->checkIsCorrect($this->answerId),
        ];

        return $this->insert($data);
    }

    /**
     * Counts user correct answer.
     *
     * @return integer
     */
    public function countUserCorrectAnswers(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(1) FROM $this->table WHERE user_id = ? AND quiz_id = ? AND is_answer_correct = ?");
        $stmt->execute([
            $this->userId,
            $this->quizId,
            1,
        ]);

        return $stmt->fetch($this->db::FETCH_COLUMN);
    }

    /**
     * Counts user answered questions.
     *
     * @return integer
     */
    public function countUserAnsweredQuestions(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(1) FROM $this->table WHERE user_id = ? AND quiz_id = ?");
        $stmt->execute([
            $this->userId,
            $this->quizId,
        ]);

        return $stmt->fetch($this->db::FETCH_COLUMN);
    }
}