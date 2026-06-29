<?php
declare(strict_types=1);

session_start();
define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/config/app.php';
require BASE_PATH . '/config/database.php';
require BASE_PATH . '/core/helpers.php';
require BASE_PATH . '/core/Database.php';
require BASE_PATH . '/core/Auth.php';
require BASE_PATH . '/core/App.php';

spl_autoload_register(function (string $class): void {
    foreach ([
        BASE_PATH . '/app/controllers/' . $class . '.php',
        BASE_PATH . '/app/models/' . $class . '.php',
        BASE_PATH . '/core/' . $class . '.php',
    ] as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

(new App())->run();
