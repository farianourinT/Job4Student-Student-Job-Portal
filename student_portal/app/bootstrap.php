<?php
// App bootstrap (MVC)
require_once __DIR__ . '/../config/config.php';

// Simple class autoloader for app/
spl_autoload_register(function($class){
    $base = __DIR__;
    $paths = [
        $base . '/models/' . $class . '.php',
        $base . '/controllers/' . $class . '.php',
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) { require_once $p; return; }
    }
});

// Shared DB connection helper (wrap existing function)
function db() {
    return getDBConnection();
}
