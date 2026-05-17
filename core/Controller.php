<?php

namespace Core;

use Core\Security\Security;
use Core\Cache\FileCache;

abstract class Controller
{
    protected Security $security;
    protected FileCache $cache;
    public function __construct()
    {
        $this->security = Security::getInstance();
        $this->cache = FileCache::getInstance();
    }
    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $f = ROOT . '/resources/Views/' . $view . '.php';
        if (!file_exists($f)) {
            http_response_code(500);
            die("View not found: $f");
        }
        require $f;
    }

    protected function json(mixed $d, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($d, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $url, int $code = 302): never
    {
        http_response_code($code);
        header("Location: $url");
        exit;
    }

    protected function back(): never
    {
        $this->redirect($_SERVER['HTTP_REFERER'] ?? BASE_URL);
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function input(string $k, mixed $def = null): mixed
    {
        return $_POST[$k] ?? $_GET[$k] ?? $def;
    }

    protected function setFlash(string $t, string $m): void
    {
        $_SESSION['flash'][$t] = $m;
    }

    protected function getFlash(): array
    {
        $f = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $f;
    }

    //checks if a user session is set.
    protected function auth(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function requireAuth(): void
    {
        if (!$this->auth()) $this->redirect(BASE_URL . '/auth/login');
    }

    protected function requireRole(string ...$roles): void
    {
        $u = $this->auth();
        if (!$u || !in_array($u['role'], $roles, true)) {
            http_response_code(403);
            $this->view('shared.403');
            exit;
        }
    }
}
