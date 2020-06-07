<?php


use Phinx\Migration\AbstractMigration;

class AnswerMigration extends AbstractMigration
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
        $this->execute('SET foreign_key_checks=0');

        $table = $this->table('answers');
        $table->addColumn('question_id', 'integer')
                ->addColumn('answer', 'text')
                ->addColumn('is_correct', 'boolean', ['default' => false])
                ->addForeignKey('question_id', 'questions', 'id')
                ->addIndex(['id','question_id'], ['name' => 'idx_id_question'])
                ->create();
    }
}
