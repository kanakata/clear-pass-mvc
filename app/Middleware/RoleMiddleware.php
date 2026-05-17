<?php

namespace App\Middleware;

class HotelMiddleware
{
    public function handle(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        if (!in_array($_SESSION['user']['role'], ['hotel', 'admin'], true)) {
            http_response_code(403);
            require ROOT . '/app/Views/shared/403.php';
            exit;
        }
    }
}
class FarmerMiddleware
{
    public function handle(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        if (!in_array($_SESSION['user']['role'], ['farmer', 'admin'], true)) {
            http_response_code(403);
            require ROOT . '/app/Views/shared/403.php';
            exit;
        }
    }
}
class AdminMiddleware
{
    public function handle(): void
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            require ROOT . '/app/Views/shared/403.php';
            exit;
        }
    }
}
