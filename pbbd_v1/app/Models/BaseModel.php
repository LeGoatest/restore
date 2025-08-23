<?php

namespace App\Models;

class BaseModel
{
    protected $db;

    public function __construct()
    {
        $this->db = new \PDO('sqlite:' . __DIR__ . '/../../pbbd.db');
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getDb()
    {
        return $this->db;
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->getTableName() . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM " . $this->getTableName());
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $this->db->prepare("INSERT INTO " . $this->getTableName() . " ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));

        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
        }
        $set = implode(', ', $set);

        $stmt = $this->db->prepare("UPDATE " . $this->getTableName() . " SET $set WHERE id = ?");
        $stmt->execute(array_merge(array_values($data), [$id]));
    }

    private function getTableName()
    {
        $class = new \ReflectionClass($this);
        return strtolower($class->getShortName()) . 's';
    }
}
