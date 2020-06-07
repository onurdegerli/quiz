<?php
namespace App\Models;

use App\Models\Model;

class User extends Model
{
    /**
     * Model table.
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * User id.
     *
     * @var integer
     */
    private $id;

    /**
     * User name.
     *
     * @var string
     */
    private $name;

    /**
     * Quiz id.
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
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Sets name.
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * Saves user data.
     *
     * @return array
     */
    public function save(): array
    {
        $data = [
            'name' => $this->name,
        ];

        return $this->insert($data);
    }

    /**
     * Gets user name by id.
     *
     * @return string
     */
    public function getNameById(): string
    {
        $row = $this->getFieldById('name', $this->id);
        return $row['name'];
    }
}