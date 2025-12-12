<?php
/**
 * CakePHP Bootstrap File
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Utility\Security;

require dirname(__DIR__) . '/vendor/autoload.php';

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT', dirname(__DIR__));
define('APP_DIR', 'src');
define('APP', ROOT . DS . APP_DIR . DS);
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', ROOT . DS . WEBROOT_DIR . DS);
define('TESTS', ROOT . DS . 'tests' . DS);
define('CACHE', ROOT . DS . 'tmp' . DS . 'cache' . DS);
define('LOGS', ROOT . DS . 'logs' . DS);
define('CONFIG', ROOT . DS . 'config' . DS);
define('RESOURCES', ROOT . DS . 'resources' . DS);
define('TMP', ROOT . DS . 'tmp' . DS);

// Detect serverless environment
$logsDir = ROOT . DS . 'logs' . DS;
define('IS_SERVERLESS', getenv('VERCEL') || getenv('AWS_LAMBDA_FUNCTION_NAME') || (file_exists($logsDir) && !is_writable($logsDir)) || !is_writable(dirname($logsDir)));

require ROOT . '/config/paths.php';

date_default_timezone_set('UTC');

// Load configuration files first
Configure::load('app', 'default', false);
Configure::load('plugins', 'default', true);

if (file_exists(CONFIG . 'app_local.php')) {
    Configure::load('app_local', 'default');
}

mb_internal_encoding(Configure::read('App.encoding') ?: 'UTF-8');

// Set up cache
// Skip file cache in serverless environments (Vercel has read-only filesystem)
if (!IS_SERVERLESS) {
    $cacheConfig = Configure::consume('Cache');
    if ($cacheConfig) {
        Cache::setConfig($cacheConfig);
    }
} else {
    // For serverless, use ArrayEngine (memory-based, no filesystem writes)
    // File cache won't work in read-only filesystem
    try {
        Cache::setConfig([
            'default' => [
                'className' => 'Cake\Cache\Engine\ArrayEngine',
            ],
            '_cake_core_' => [
                'className' => 'Cake\Cache\Engine\ArrayEngine',
                'prefix' => 'myapp_cake_core_',
                'serialize' => true,
                'duration' => '+2 minutes',
            ],
            '_cake_model_' => [
                'className' => 'Cake\Cache\Engine\ArrayEngine',
                'prefix' => 'myapp_cake_model_',
                'serialize' => true,
                'duration' => '+2 minutes',
            ],
        ]);
    } catch (\Exception $e) {
        // If cache setup fails, continue without cache
        error_log('Cache setup failed: ' . $e->getMessage());
    }
}

// Set up database connections
$datasources = Configure::consume('Datasources');
if ($datasources) {
    try {
        ConnectionManager::setConfig($datasources);
        // Test connection
        if (!IS_SERVERLESS) {
            $connection = ConnectionManager::get('default');
            $connection->connect();
        }
    } catch (\Exception $e) {
        // Log database connection error but don't fail bootstrap
        error_log('Database connection error: ' . $e->getMessage());
        if (IS_SERVERLESS) {
            // In serverless, we'll let it fail when actually used
            // This allows the app to bootstrap even if DB is temporarily unavailable
        } else {
            throw $e;
        }
    }
}

// Set up logging
// Skip file logging in serverless environments (Vercel has read-only filesystem)
if (!IS_SERVERLESS) {
    $logConfig = Configure::consume('Log');
    if ($logConfig && !empty($logConfig)) {
        Log::setConfig($logConfig);
    }
} else {
    // For serverless, disable file logging - errors will be captured by Vercel's error handling
    // Don't configure any log engines to avoid filesystem writes
}

// Set security salt
$salt = Configure::consume('Security.salt');
if ($salt && $salt !== '__SALT__' && $salt !== '__CHANGE_THIS_SALT__') {
    Security::setSalt($salt);
} else {
    // Generate a default salt if not set
    Security::setSalt('default_salt_change_in_production_' . md5(__FILE__));
}

$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    $errorConfig = Configure::read('Error');
    if ($errorConfig) {
        (new ErrorTrap($errorConfig))->register();
        (new ExceptionTrap($errorConfig))->register();
    }
}

if (!$isCli) {
    $errorConfig = Configure::read('Error');
    if ($errorConfig) {
        (new ErrorTrap($errorConfig))->register();
        (new ExceptionTrap($errorConfig))->register();
    }
}

if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
}

if (Configure::read('debug') && file_exists(ROOT . '/vendor/cakephp/cakephp-codesniffer/CakePHP')) {
    Configure::write('CodeSniffer', [
        'phpExecutable' => PHP_BINARY,
    ]);
}

// Initialize Router before creating Application
\Cake\Routing\Router::resetRoutes();

try {
    return new \App\Application(CONFIG);
} catch (\Throwable $e) {
    // If Application creation fails, throw with context
    error_log('Application creation failed: ' . $e->getMessage());
    error_log('File: ' . $e->getFile() . ':' . $e->getLine());
    throw $e;
}
