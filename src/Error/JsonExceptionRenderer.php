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
        $exception = $this->getError();
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

        // Add debug information if debug mode is enabled
        if (Configure::read('debug')) {
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
        }

        // Create and return JSON response directly
        return new Response([
            'status' => $code,
            'type' => 'application/json',
            'body' => json_encode($response, JSON_PRETTY_PRINT),
        ]);
    }
}

