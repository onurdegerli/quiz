<?php
namespace App\Models;

use App\Models\Model;

class Answer extends Model
{
    /**
     * Model table.
     *
     * @var string
     */
    protected $table = 'answers';

    /**
     * Id.
     *
     * @var int
     */
    private $id;

    /**
     * Quiz id.
     *
     * @var int
     */
    private $quizId;

    /**
     * Question id.
     *
     * @var int
     */
    private $questionId;

    /**
     * Sets answer id.
     *
     * @param integer $id
     * @return void
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Sets quiz id which is related to answer.
     *
     * @param integer $quizId
     * @return void
     */
    public function setQuizId(int $quizId)
    {
        $this->quizId = $quizId;
    }

    /**
     * Sets question id which is related to answer.
     *
     * @param integer $questionId
     * @return void
     */
    public function setQuestionId(int $questionId)
    {
        $this->questionId = $questionId;
    }

    /**
     * Checks if the answer is correct.
     *
     * @param integer $answerId
     * @return integer
     */
    public function checkIsCorrect(int $answerId): int
    {
        $row = $this->get($answerId);
        return $row['is_correct'];
    }

    /**
     * Get answer by question id.
     *
     * @return array
     */
    public function getByQuestionId(): array
    {
        return $this->getAllByRelationId('question_id', $this->questionId);
    }

    /**
     * Checks if the answer is related to question and quiz.
     *
     * @return boolean
     */
    public function checkIfAnswerRelatedToQuizAndQuestion(): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(1) 
                            FROM $this->table a
                            LEFT JOIN questions qe
                                ON a.question_id = qe.id
                            LEFT JOIN quizzes qi
                                ON qe.quiz_id = qi.id
                            WHERE a.id = :answer_id 
                                AND qe.id = :question_id
                                AND qi.id = :quiz_id");

        $stmt->bindValue(':answer_id', $this->id);
        $stmt->bindValue(':question_id', $this->questionId);
        $stmt->bindValue(':quiz_id', $this->quizId);
        $stmt->execute();  
        $count = $stmt->fetch($this->db::FETCH_COLUMN);

        return $count > 0 ? TRUE : FALSE;
    }
}