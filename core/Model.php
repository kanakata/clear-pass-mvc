<?php

namespace Core;

use Core\Database\Database;
use Core\Cache\FileCache;

abstract class Model
{
    protected Database $db;
    protected FileCache $cache;
    protected string $table = '';
    protected string $primaryKey = 'id';
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->cache = FileCache::getInstance();
    }
    public function find(int $id): array|false
    {
        return $this->cache->remember("{$this->table}:{$id}", function () use ($id) {
            return $this->db->fetchOne("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}`=?", [$id]);
        }, 120);
    }
    public function findAll(string $w = '', array $p = [], int $lim = 50, int $off = 0): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        if ($w) $sql .= " WHERE $w";
        $sql .= " LIMIT $lim OFFSET $off";
        return $this->db->fetchAll($sql, $p);
    }
    public function count(string $w = '', array $p = []): int
    {
        $sql = "SELECT COUNT(*) as cnt FROM `{$this->table}`";
        if ($w) $sql .= " WHERE $w";
        $r = $this->db->fetchOne($sql, $p);
        return (int)($r['cnt'] ?? 0);
    }
    public function create(array $data): string
    {
        $cols = implode(',', array_map(fn($k) => "`$k`", array_keys($data)));
        $ph = implode(',', array_fill(0, count($data), '?'));
        $id = $this->db->insert("INSERT INTO `{$this->table}` ($cols) VALUES ($ph)", array_values($data));
        $this->cache->delete("{$this->table}:$id");
        return $id;
    }
    public function update(int $id, array $data): int
    {
        $sets = implode(',', array_map(fn($k) => "`$k`=?", array_keys($data)));
        $rows = $this->db->execute("UPDATE `{$this->table}` SET $sets WHERE `{$this->primaryKey}`=?", [...array_values($data), $id]);
        $this->cache->delete("{$this->table}:$id");
        return $rows;
    }
    public function delete(int $id): int
    {
        $rows = $this->db->execute("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}`=?", [$id]);
        $this->cache->delete("{$this->table}:$id");
        return $rows;
    }
}
