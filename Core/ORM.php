<?php

namespace Core;

use PDO;
use PDOException;

class ORM
{
    protected $pdo;
    protected $table;

    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=PiePHP;charset=utf8';
        $username = 'root';
        $password = 'root';

        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erreur de connexion : ' . $e->getMessage());
        }
    }

    public function create(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public function read(array $conditions): ?array
    {
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = :$key";
        }
        $whereClause = implode(' AND ', $where);

        $sql = "SELECT * FROM {$this->table} WHERE $whereClause";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
?>