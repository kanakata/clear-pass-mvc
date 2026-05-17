<?php

namespace Core\Database;

use PDO;
use PDOException;

class Database
{
    private static ?self $instance = null;
    private PDO $pdo;
    private function __construct()
    {
        $cfg = require ROOT . '/config/database.php';
        $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['dbname']};charset={$cfg['charset']}";
        try {
            $this->pdo = new PDO($dsn, $cfg['username'], $cfg['password'], $cfg['options']);
        } catch (PDOException $e) {
            error_log('DB:' . $e->getMessage());
            die('Database unavailable');
        }
    }
    public static function getInstance(): self
    {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
    public function query(string $sql, array $p = []): \PDOStatement
    {
        $s = $this->pdo->prepare($sql);
        $s->execute($p);
        return $s;
    }
    public function fetchAll(string $sql, array $p = []): array
    {
        return $this->query($sql, $p)->fetchAll();
    }
    public function fetchOne(string $sql, array $p = []): array|false
    {
        return $this->query($sql, $p)->fetch();
    }
    public function insert(string $sql, array $p = []): string
    {
        $this->query($sql, $p);
        return $this->pdo->lastInsertId();
    }
    public function execute(string $sql, array $p = []): int
    {
        return $this->query($sql, $p)->rowCount();
    }
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }
    public function commit(): void
    {
        $this->pdo->commit();
    }
    public function rollback(): void
    {
        $this->pdo->rollBack();
    }
    private function __clone() {}
}
