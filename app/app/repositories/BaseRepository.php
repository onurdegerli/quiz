<?php declare(strict_types=1);

namespace App\Repositories;

use Exception;
use PDO;

abstract class BaseRepository
{
    protected PDO $db;

    protected string $table;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $sth = $this->db->prepare("SELECT * FROM $this->table");
        $sth->execute();

        return $sth->fetchAll();
    }

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
            $row = $this->get((int)$lastInsertedId);

            $this->db->commit();

            return $row;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function get(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getFieldById(string $field, int $id): array
    {
        $stmt = $this->db->prepare("SELECT $field FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getByRelationId(string $field, int $fieldVal, int $limit = 1, int $offset = 0)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM $this->table WHERE $field = :field ORDER BY id ASC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':field', $fieldVal);
        $stmt->bindValue(':limit', (int)$limit, $this->db::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, $this->db::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function getByRelationIdGreaterThan(string $field, int $fieldVal, int $id, int $limit = 1, int $offset = 0)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM $this->table WHERE $field = :field AND id > :id ORDER BY id ASC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':field', $fieldVal);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':limit', (int)$limit, $this->db::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, $this->db::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function getAllByRelationId(string $field, int $fieldVal): array
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE $field = ?");
        $stmt->execute([$fieldVal]);

        return $stmt->fetchAll();
    }

    public function countByRelationId(string $field, int $fieldVal): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(1) FROM $this->table WHERE $field = ?");
        $stmt->execute(
            [
                $fieldVal,
            ]
        );

        return $stmt->fetch($this->db::FETCH_COLUMN);
    }

    public function countByRelationIdGreaterThan(string $field, int $fieldVal, int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(1) FROM $this->table WHERE $field = ? AND id > ?");
        $stmt->execute(
            [
                $fieldVal,
                $id
            ]
        );

        return $stmt->fetch($this->db::FETCH_COLUMN);
    }

    private static function getFields(array $data): string
    {
        return implode(',', array_keys($data));
    }

    private static function getPlaceholders(array $data): string
    {
        return implode(',', array_fill(0, count($data), '?'));
    }
}