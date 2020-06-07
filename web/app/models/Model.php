<?php

namespace App\Models;

abstract class Model {

    /**
     * DB instance.
     *
     * @var \Core\Db
     */
    protected $db;

    /**
     * Model table.
     *
     * @var string
     */
    protected $table;

    /**
     * Constructor.
     *
     * @param \PDO $db
     * @param string $table
     * @return void
     */
    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    /**
     * Retrives all data from related table.
     *
     * @return array
     */
    public function getAll(): array
    {
        $sth = $this->db->prepare("SELECT * FROM $this->table");
        $sth->execute();
        return $sth->fetchAll();
    }

    /**
     * Inserts data to DB with a given dataset.
     *
     * @param array $data
     * @return array
     */
    public function insert(array $data): array
    {
        $this->db->beginTransaction();

        try {
            $fields = self::getFields($data);
            $placeholders = self::getPlaceholders($data);
    
            $sql = "INSERT INTO `$this->table` ($fields) VALUES ($placeholders)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_values($data));
    
            $lastInsertedId = $this->db->lastInsertId();
            $row = $this->get($lastInsertedId);
    
            $this->db->commit();

            return $row;
        } catch(Exception $e) {
            $this->db->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Gets data with a given id.
     *
     * @param integer $id
     * @return array
     */
    public function get(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Gets a specific field with a given id.
     *
     * @param string $field
     * @param integer $id
     * @return array
     */
    public function getFieldById(string $field, int $id): array
    {
        $stmt = $this->db->prepare("SELECT $field FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Gets data with the given field. 
     *
     * @param string $field
     * @param integer $fieldVal
     * @param integer $limit
     * @param integer $offset
     * @return void
     */
    public function getByRelationId(string $field, int $fieldVal, int $limit = 1, int $offset = 0)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE $field = :field ORDER BY id ASC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':field', $fieldVal);
        $stmt->bindValue(':limit', (int) $limit, $this->db::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, $this->db::PARAM_INT);
        $stmt->execute();  
        return $stmt->fetch();
    }

    /**
     * Gets data with the given field and greater than id. 
     *
     * @param string $field
     * @param integer $fieldVal
     * @param integer $currentQuestionId
     * @param integer $limit
     * @param integer $offset
     * @return void
     */
    public function getByRelationIdGreaterThan(string $field, int $fieldVal, int $id, int $limit = 1, int $offset = 0)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE $field = :field AND id > :id ORDER BY id ASC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':field', $fieldVal);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':limit', (int) $limit, $this->db::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, $this->db::PARAM_INT);
        $stmt->execute();  
        return $stmt->fetch();
    }

    /**
     * Gets all data with the given field. 
     *
     * @param string $field
     * @param integer $fieldVal
     * @return array
     */
    public function getAllByRelationId(string $field, int $fieldVal): array
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE $field = ?");
        $stmt->execute([$fieldVal]);
        return $stmt->fetchAll();
    }

    /**
     * Counts all data with the given field. 
     *
     * @param string $field
     * @param integer $fieldVal
     * @return integer
     */
    public function countByRelationId(string $field, int $fieldVal): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(1) FROM $this->table WHERE $field = ?");
        $stmt->execute([
            $fieldVal,
        ]);
        return $stmt->fetch($this->db::FETCH_COLUMN);
    }

    /**
     * Counts all data with the given field. 
     *
     * @param string $field
     * @param integer $fieldVal
     * @return integer
     */
    public function countByRelationIdGreaterThan(string $field, int $fieldVal, int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(1) FROM $this->table WHERE $field = ? AND id > ?");
        $stmt->execute([
            $fieldVal,
            $id
        ]);
        return $stmt->fetch($this->db::FETCH_COLUMN);
    }

    /**
     * Prepares the fields string for PDO statement.
     *
     * @param array $data
     * @return string
     */
    private static function getFields(array $data): string
    {
        return implode(',', array_keys($data));
    }

    /**
     * Prepares placeholders string for PDO statement.
     *
     * @param array $data
     * @return string
     */
    private static function getPlaceholders(array $data): string
    {
        return implode(',', array_fill(0, count($data), '?'));
    }
}