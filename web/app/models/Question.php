<?php
namespace App\Models;

use App\Models\Model;

class Question extends Model
{
    /**
     * Model table.
     *
     * @var string
     */
    protected $table = 'questions';

    /**
     * Question id.
     *
     * @var integer
     */
    private $id;

    /**
     * Quiz id which is related to question.
     *
     * @var integer
     */
    private $quizId;

    /**
     * Sets id.
     *
     * @param integer $id
     * @return void
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Sets quiz id.
     *
     * @param integer $quizId
     * @return void
     */
    public function setQuizId(int $quizId)
    {
        $this->quizId = $quizId;
    }

    /**
     * Gets first question of a quiz.
     *
     * @return array
     */
    public function getFirstQuestionByQuizId(): array
    {
        return $this->getByRelationId('quiz_id', $this->quizId);
    }

    /**
     * Gets next question which is after a previous question.
     *
     * @return array
     */
    public function getFirstQuestionByQuizIdGreaterThan(): array
    {
        return $this->getByRelationIdGreaterThan('quiz_id', $this->quizId, $this->id);
    }

    /**
     * Counts questions by quiz id.
     *
     * @return integer
     */
    public function countQuestionByQuizId(): int
    {
        return $this->countByRelationId('quiz_id', $this->quizId);
    }

    public function countRemainingQuestionByQuizId(): int
    {
        return $this->countByRelationIdGreaterThan('quiz_id', $this->quizId, $this->id);
    }
}