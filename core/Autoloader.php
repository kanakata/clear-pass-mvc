<?php
spl_autoload_register(function (string $class): void {
    $map = ['Core\\' => ROOT . '/core/', 'App\\Controllers\\' => ROOT . '/app/Controllers/', 'App\\Models\\' => ROOT . '/app/Models/', 'App\\Middleware\\' => ROOT . '/app/Middleware/'];
    foreach ($map as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $f = $dir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
            if (file_exists($f)) {
                require_once $f;
                return;
            }
        }
    }
    $dirs = [ROOT . '/core/', ROOT . '/core/Database/', ROOT . '/core/Cache/', ROOT . '/core/Security/', ROOT . '/app/Controllers/', ROOT . '/app/Models/', ROOT . '/app/Middleware/'];
    $short = basename(str_replace('\\', '/', $class));
    foreach ($dirs as $dir) {
        $f = $dir . $short . '.php';
        if (file_exists($f)) {
            require_once $f;
            return;
        }
    }
});
