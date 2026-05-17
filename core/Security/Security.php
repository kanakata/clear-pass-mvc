<?php

namespace Core\Security;

class Security
{
    private static ?self $instance = null;
    private string $rlDir;
    private array $cfg;
    private function __construct()
    {
        $this->cfg = require ROOT . '/config/app.php';
        $this->rlDir = ROOT . '/storage/logs/';
        if (!is_dir($this->rlDir)) mkdir($this->rlDir, 0755, true);
    }
    public static function getInstance(): self
    {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    public function sendSecureHeaders(): void
    {
        if (headers_sent()) return;
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: blob:; connect-src 'self';");
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        header_remove('X-Powered-By');
        header_remove('Server');
    }

    public function escape(mixed $v): string
    {
        return htmlspecialchars((string)$v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function escapeJs(mixed $v): string
    {
        return json_encode((string)$v, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    public function sanitizeString(string $s, int $max = 255): string
    {
        $c = trim(strip_tags($s));
        $c = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $c);
        return mb_substr($c, 0, $max);
    }

    public function sanitizeEmail(string $e): string|false
    {
        $c = filter_var(trim($e), FILTER_SANITIZE_EMAIL);
        return filter_var($c, FILTER_VALIDATE_EMAIL) ? strtolower($c) : false;
    }

    public function sanitizeInt(mixed $v): int|false
    {
        return filter_var($v, FILTER_VALIDATE_INT) !== false ? (int)$v : false;
    }

    public function sanitizeFloat(mixed $v): float|false
    {
        return filter_var($v, FILTER_VALIDATE_FLOAT) !== false ? (float)$v : false;
    }

    public function sanitizePath(string $p): string
    {
        $p = str_replace(['../', '..\\', "\0"], '', $p);
        return preg_replace('/[^a-zA-Z0-9_\-\.\/]/', '', $p);
    }
    public function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    public function validateCsrf(string $t): bool
    {
        if (empty($_SESSION['csrf_token'])) return false;
        return hash_equals($_SESSION['csrf_token'], $t);
    }

    public function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . $this->escape($this->generateCsrfToken()) . '">';
    }

    public function verifyCsrf(): void
    {
        $t = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!$this->validateCsrf($t)) {
            http_response_code(403);
            die('Invalid CSRF token');
        }
    }

    private function rlFile(string $a, string $id): string
    {
        return $this->rlDir . 'rl_' . md5($a . $id) . '.json';
    }

    public function checkRateLimit(string $action, string $id = ''): bool
    {
        $id = $id ?: $this->getIp();
        $lim = $this->cfg['rate_limit'][$action] ?? ['attempts' => 20, 'window' => 60];
        $f = $this->rlFile($action, $id);
        $data = ['hits' => [], 'blocked_until' => 0];
        if (file_exists($f)) $data = json_decode(file_get_contents($f), true) ?: $data;
        $now = time();
        $win = $lim['window'];
        if (isset($data['blocked_until']) && $now < $data['blocked_until']) return false;
        $data['hits'] = array_values(array_filter($data['hits'], fn($t) => ($now - $t) < $win));
        $data['hits'][] = $now;
        if (count($data['hits']) > $lim['attempts']) {
            $data['blocked_until'] = $now + $win;
            file_put_contents($f, json_encode($data), LOCK_EX);
            return false;
        }
        file_put_contents($f, json_encode($data), LOCK_EX);
        return true;
    }

    public function getRemainingAttempts(string $action, string $id = ''): int
    {
        $id = $id ?: $this->getIp();
        $lim = $this->cfg['rate_limit'][$action] ?? ['attempts' => 20, 'window' => 60];
        $f = $this->rlFile($action, $id);
        if (!file_exists($f)) return $lim['attempts'];
        $data = json_decode(file_get_contents($f), true) ?: ['hits' => []];
        $now = time();
        $win = $lim['window'];
        $active = array_filter($data['hits'] ?? [], fn($t) => ($now - $t) < $win);
        return max(0, $lim['attempts'] - count($active));
    }

    public function startSecureSession(): void
    {
        if (session_status() !== PHP_SESSION_NONE) return;
        $cfg = $this->cfg;
        session_name($cfg['session_name']);
        session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'domain' => '', 'secure' => isset($_SERVER['HTTPS']), 'httponly' => true, 'samesite' => 'Strict']);
        session_start();
        if (!isset($_SESSION['_init'])) {
            session_regenerate_id(true);
            $_SESSION['_init'] = true;
            $_SESSION['_ip'] = $this->getIp();
            $_SESSION['_ua'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $_SESSION['_created'] = time();
        }
        if ($_SESSION['_ip'] !== $this->getIp() || $_SESSION['_ua'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) $this->destroySession();
        if (isset($_SESSION['_last']) && (time() - $_SESSION['_last']) > $cfg['session_lifetime']) $this->destroySession();
        $_SESSION['_last'] = time();
    }

    public function destroySession(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    public function validateUpload(array $file, array $mimes = [], int $max = 0): array
    {
        $errors = [];
        $max = $max ?: $this->cfg['upload_max'];
        $mimes = $mimes ?: $this->cfg['allowed_img'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload error: ' . $file['error'];
            return ['valid' => false, 'errors' => $errors];
        }
        if ($file['size'] > $max) $errors[] = 'File too large (max ' . ($max / 1024 / 1024) . 'MB)';
        $fi = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $fi->file($file['tmp_name']);
        if (!in_array($mime, $mimes, true)) $errors[] = 'Invalid file type: ' . $mime;
        $c = file_get_contents($file['tmp_name'], false, null, 0, 1024);
        if (preg_match('/<\?php|<\?=/i', $c)) $errors[] = 'Disallowed file content';
        return ['valid' => empty($errors), 'errors' => $errors, 'mime' => $mime ?? ''];
    }

    public function safeFilename(string $n): string
    {
        $n = pathinfo($n, PATHINFO_FILENAME);
        $n = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $n);
        return substr($n, 0, 60) . '_' . bin2hex(random_bytes(4));
    }

    public function hashPassword(string $p): string
    {
        return password_hash($p, PASSWORD_ARGON2ID, ['memory_cost' => 65536, 'time_cost' => 4, 'threads' => 1]);
    }

    public function verifyPassword(string $p, string $h): bool
    {
        return password_verify($p, $h);
    }

    public function validatePasswordStrength(string $p): array
    {
        $e = [];
        if (strlen($p) < 8) $e[] = 'At least 8 characters required';
        if (!preg_match('/[A-Z]/', $p)) $e[] = 'Needs an uppercase letter';
        if (!preg_match('/[a-z]/', $p)) $e[] = 'Needs a lowercase letter';
        if (!preg_match('/[0-9]/', $p)) $e[] = 'Needs a number';
        return $e;
    }

    public function getIp(): string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $k) {
            if (!empty($_SERVER[$k])) {
                $ip = trim(explode(',', $_SERVER[$k])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) return $ip;
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public function generateToken(int $b = 32): string
    {
        return bin2hex(random_bytes($b));
    }

    private function __clone() {}
}
