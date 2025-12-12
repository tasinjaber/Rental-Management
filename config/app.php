<?php
/*
 * CakePHP Configuration File
 */
return [
    'debug' => filter_var(env('DEBUG', getenv('APP_ENV') !== 'production' ? true : false), FILTER_VALIDATE_BOOLEAN),
    'App' => [
        'namespace' => 'App',
        'encoding' => env('APP_ENCODING', 'UTF-8'),
        'defaultLocale' => env('APP_DEFAULT_LOCALE', 'en_US'),
        'defaultTimezone' => env('APP_DEFAULT_TIMEZONE', 'UTC'),
        'base' => false,  // Auto-detect from SCRIPT_NAME (set in webroot/index.php)
        'dir' => 'src',
        'webroot' => 'webroot',
        'wwwRoot' => WWW_ROOT,
        'fullBaseUrl' => false,
        'imageBaseUrl' => 'img/',
        'cssBaseUrl' => 'css/',
        'jsBaseUrl' => 'js/',
        'paths' => [
            'plugins' => [ROOT . DS . 'plugins' . DS],
            'templates' => [ROOT . DS . 'templates' . DS],
            'locales' => [RESOURCES . 'locales' . DS],
        ],
    ],
    'Security' => [
        'salt' => env('SECURITY_SALT', '__SALT__'),
        'cookieKey' => env('SECURITY_COOKIE_KEY', null),
    ],
    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => env('DB_HOST', 'localhost'),
            'username' => env('DB_USER', 'root'),
            'password' => env('DB_PASS', ''),
            'database' => env('DB_NAME', 'rental_management'),
            'encoding' => 'utf8mb4',
            'timezone' => 'UTC',
            'flags' => [],
            'cacheMetadata' => true,
            'log' => false,
            'quoteIdentifiers' => false,
            'url' => env('DATABASE_URL', null),
        ],
    ],
    'Error' => [
        'errorLevel' => E_ALL & ~E_USER_DEPRECATED,
        'skipLog' => [],
        'log' => !(getenv('VERCEL') || getenv('AWS_LAMBDA_FUNCTION_NAME')),
        'trace' => true,
        'exceptionRenderer' => \Cake\Error\ExceptionRenderer::class,
        'ignoredDeprecationPaths' => [
            'config/routes.php',
        ],
    ],
    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            'host' => 'localhost',
            'port' => 587,
            'timeout' => 30,
            'username' => null,
            'password' => null,
            'client' => null,
            'tls' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],
    'Email' => [
        'default' => [
            'from' => ['you@localhost' => 'Rental Management'],
            'transport' => 'default',
            'charset' => 'utf-8',
            'headerCharset' => 'utf-8',
        ],
    ],
    'Cache' => [
        'default' => [
            'className' => 'File',
            'path' => CACHE,
            'url' => env('CACHE_DEFAULT_URL', null),
        ],
        '_cake_core_' => [
            'className' => 'File',
            'prefix' => 'myapp_cake_core_',
            'path' => CACHE . 'persistent/',
            'serialize' => true,
            'duration' => '+2 minutes',
            'url' => env('CACHE_CAKECORE_URL', null),
        ],
        '_cake_model_' => [
            'className' => 'File',
            'prefix' => 'myapp_cake_model_',
            'path' => CACHE . 'models/',
            'serialize' => true,
            'duration' => '+2 minutes',
            'url' => env('CACHE_CAKEMODEL_URL', null),
        ],
    ],
    'Log' => [],
    'Session' => [
        'defaults' => 'php',
    ],
];
