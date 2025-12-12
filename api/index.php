<?php
/**
 * Vercel Serverless Function Entry Point for CakePHP
 * This file routes all requests to the CakePHP application
 */
use Cake\Http\Server;

// Set error reporting - show all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');

// Set error handler to catch all errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Set exception handler
set_exception_handler(function($e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Uncaught Exception',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => explode("\n", $e->getTraceAsString()),
    ], JSON_PRETTY_PRINT);
    exit;
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
    
    // Return a proper error response
    http_response_code(500);
    header('Content-Type: application/json');
    
    // Always show error details for debugging
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => explode("\n", $e->getTraceAsString()),
    ], JSON_PRETTY_PRINT);
    exit;
}

