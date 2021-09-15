<?php
    require '../env.php';

    define('ROOT_ENDPOINT', false);
    define('DEBUG', true);

    define('ROOT_ENDPOINT_CONTROLLER', ['file' => '../root_controller.php', 'controller' => '\App\RootController']);

    define('APP_RESOURCES', [
        'users',
        'products',
        'orders'
    ]);

    define('DATABASE', [
        'type'      => DATABASE_TYPE,
        'host'      => DATABASE_HOST,
        'port'      => DATABASE_PORT,
        'name'      => DATABASE_NAME,
        'login'     => DATABASE_LOGIN,
        'password'  => DATABASE_PASSWORD
    ]);
