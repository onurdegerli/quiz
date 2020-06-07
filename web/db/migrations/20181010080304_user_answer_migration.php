<?php


use Phinx\Migration\AbstractMigration;

class UserAnswerMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('user_answers');
        $table->addColumn('user_id', 'integer')
              ->addColumn('quiz_id', 'integer')
              ->addColumn('question_id', 'integer')
              ->addColumn('answer_id', 'integer')
              ->addColumn('is_answer_correct', 'boolean', ['default' => false])
              ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addForeignKey('user_id', 'users', 'id')
              ->addForeignKey('quiz_id', 'quizzes', 'id')
              ->addForeignKey('question_id', 'questions', 'id')
              ->addForeignKey('answer_id', 'answers', 'id')
              ->addIndex(['user_id','quiz_id'], ['name' => 'idx_user_quiz'])
              ->addIndex(['user_id','question_id'], ['name' => 'idx_user_question'])
              ->addIndex(['user_id','answer_id'], ['name' => 'idx_user_answer'])
              ->addIndex(['user_id','quiz_id', 'question_id', 'answer_id'], ['name' => 'idx_user_quiz_question_answer'])
              ->create();
    }
}
