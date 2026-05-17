<?php

namespace Core\Cache;

class FileCache
{
    private string $dir;
    private int $ttl;
    private static ?self $instance = null;
    private function __construct()
    {
        $this->dir = ROOT . '/storage/cache/';
        $this->ttl = (require ROOT . '/config/app.php')['cache_ttl'];
        if (!is_dir($this->dir)) mkdir($this->dir, 0755, true);
    }
    public static function getInstance(): self
    {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function path(string $k): string
    {
        return $this->dir . md5($k) . '.cache';
    }

    public function set(string $k, mixed $v, int $ttl = 0): bool
    {
        $ttl = $ttl ?: $this->ttl;
        return (bool)file_put_contents($this->path($k), serialize(['e' => time() + $ttl, 'd' => $v]), LOCK_EX);
    }

    public function get(string $k): mixed
    {
        $f = $this->path($k);
        if (!file_exists($f)) return null;
        $r = file_get_contents($f);
        if (!$r) return null;
        $p = unserialize($r);
        if (!$p || time() > $p['e']) {
            @unlink($f);
            return null;
        }
        return $p['d'];
    }

    public function has(string $k): bool
    {
        return $this->get($k) !== null;
    }

    public function delete(string $k): bool
    {
        $f = $this->path($k);
        return file_exists($f) && unlink($f);
    }

    public function flush(): void
    {
        foreach (glob($this->dir . '*.cache') as $f) @unlink($f);
    }

    public function remember(string $k, callable $cb, int $ttl = 0): mixed
    {
        $v = $this->get($k);
        if ($v !== null) return $v;
        $v = $cb();
        $this->set($k, $v, $ttl);
        return $v;
    }

    private function __clone() {}
}
