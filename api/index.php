<?php
/**
 * Vercel Serverless Function Entry Point for CakePHP
 * This file routes all requests to the CakePHP application
 */
use Cake\Http\Server;

// Set error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', '0'); // Don't display, but log

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
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => getenv('APP_ENV') === 'production' 
            ? 'An error occurred' 
            : $e->getMessage(),
    ]);
    exit;
}

