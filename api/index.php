<?php
/**
 * Vercel Serverless Function Entry Point for CakePHP
 * This file routes all requests to the CakePHP application
 */
use Cake\Http\Server;

// Set error reporting - show all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', '0'); // Don't display raw errors
ini_set('log_errors', '1');

// Function to output JSON error
function outputJsonError($e, $title = 'Internal Server Error') {
    // Clear any previous output
    if (ob_get_level()) {
        ob_clean();
    }
    
    http_response_code(500);
    header('Content-Type: application/json');
    
    $response = [
        'error' => $title,
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => explode("\n", $e->getTraceAsString()),
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

// Set error handler to catch all errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}, E_ALL);

// Set exception handler
set_exception_handler(function($e) {
    outputJsonError($e, 'Uncaught Exception');
});

// Set shutdown handler to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        outputJsonError(new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']), 'Fatal Error');
    }
});

try {
    require dirname(__DIR__) . '/vendor/autoload.php';

    // Bootstrap the CakePHP application
    $app = require dirname(__DIR__) . '/config/bootstrap.php';

    // Create and run the server
    $server = new Server($app);
    $server->emit($server->run());
} catch (\Throwable $e) {
    // Log error details for debugging
    error_log('Fatal Error: ' . $e->getMessage());
    error_log('File: ' . $e->getFile() . ':' . $e->getLine());
    error_log('Trace: ' . $e->getTraceAsString());
    
    // Check if it's a database connection error
    $message = $e->getMessage();
    if (stripos($message, 'database') !== false || 
        stripos($message, 'connection') !== false ||
        stripos($message, 'mysql') !== false ||
        stripos($message, 'sqlstate') !== false) {
        error_log('Database connection error detected');
    }
    
    // Output JSON error
    outputJsonError($e);
}

