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

    public function create($table, $fields)
    {
        $columns = implode(', ', array_keys($fields));
        $placeholders = ':' . implode(', :', array_keys($fields));

        $sql = "INSERT INTO {$table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute($fields)) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function read($table, $id)
    {
        $sql = "SELECT * FROM {$table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function update($table, $id, $fields)
    {
        $updates = [];
        foreach (array_keys($fields) as $key) {
            $updates[] = "$key = :$key";
        }

        $updateString = implode(', ', $updates);
        $sql = "UPDATE {$table} SET {$updateString} WHERE id = :id";

        $fields['id'] = $id;
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($fields);
    }

    public function delete($table, $id)
    {
        $sql = "DELETE FROM {$table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    public function find($table, $params = [
        'WHERE' => '1',
        'ORDER BY' => 'id ASC',
        'LIMIT' => ''
    ])
    {
        $params = array_merge([
            'WHERE' => '1',
            'ORDER BY' => 'id ASC',
            'LIMIT' => ''
        ], $params);

        $sql = "SELECT * FROM {$table} WHERE {$params['WHERE']}";

        if (!empty($params['ORDER BY'])) {
            $sql .= " ORDER BY {$params['ORDER BY']}";
        }

        if (!empty($params['LIMIT'])) {
            $sql .= " LIMIT {$params['LIMIT']}";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>