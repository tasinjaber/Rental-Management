<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Core\Configure;
use Cake\Error\Renderer\WebExceptionRenderer;
use Cake\Http\Response;

/**
 * Custom Exception Renderer that returns JSON responses
 */
class JsonExceptionRenderer extends WebExceptionRenderer
{
    /**
     * Render the exception as JSON
     */
    public function render(): Response
    {
        try {
            // Get exception from parent class property (WebExceptionRenderer stores it)
            $reflection = new \ReflectionClass($this);
            $exception = null;
            
            // Try to get exception from various possible property names
            if ($reflection->hasProperty('exception')) {
                $prop = $reflection->getProperty('exception');
                $prop->setAccessible(true);
                $exception = $prop->getValue($this);
            } elseif ($reflection->hasProperty('error')) {
                $prop = $reflection->getProperty('error');
                $prop->setAccessible(true);
                $exception = $prop->getValue($this);
            } else {
                // Try parent class
                $parent = $reflection->getParentClass();
                if ($parent && $parent->hasProperty('exception')) {
                    $prop = $parent->getProperty('exception');
                    $prop->setAccessible(true);
                    $exception = $prop->getValue($this);
                }
            }
            
            if (!$exception instanceof \Throwable) {
                throw new \RuntimeException('Could not retrieve exception from renderer');
            }
            
            $code = 500;
            $message = $exception->getMessage() ?: 'An error occurred';
            
            // Get HTTP status code from exception
            if ($exception instanceof \Cake\Http\Exception\HttpException) {
                $code = $exception->getCode();
            } elseif (method_exists($exception, 'getCode')) {
                $exceptionCode = $exception->getCode();
                if ($exceptionCode >= 100 && $exceptionCode < 600) {
                    $code = $exceptionCode;
                }
            }

            $response = [
                'error' => get_class($exception),
                'code' => $code,
                'message' => $message,
            ];

            // Always add debug information for now
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['trace'] = explode("\n", $exception->getTraceAsString());
            
            if ($exception->getPrevious()) {
                $response['previous'] = [
                    'message' => $exception->getPrevious()->getMessage(),
                    'file' => $exception->getPrevious()->getFile(),
                    'line' => $exception->getPrevious()->getLine(),
                ];
            }

            // Create and return JSON response directly
            return new Response([
                'status' => $code,
                'type' => 'application/json',
                'body' => json_encode($response, JSON_PRETTY_PRINT),
            ]);
        } catch (\Throwable $e) {
            // If renderer itself fails, output JSON directly
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Exception Renderer Error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString()),
            ], JSON_PRETTY_PRINT);
            exit;
        }
    }
}

