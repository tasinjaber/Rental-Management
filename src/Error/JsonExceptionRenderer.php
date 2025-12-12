<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Core\Configure;
use Cake\Error\ExceptionRenderer;

/**
 * Custom Exception Renderer that returns JSON responses
 */
class JsonExceptionRenderer extends ExceptionRenderer
{
    /**
     * Render the exception as JSON
     */
    protected function _outputMessage($template)
    {
        $code = $this->code ?: 500;
        $message = $this->message ?: 'An error occurred';
        $error = $this->error ?: 'error';
        $exception = $this->exception;

        $response = [
            'error' => $error,
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

        // Set response headers and body
        $responseObj = $this->controller->getResponse()
            ->withType('application/json')
            ->withStatus($code)
            ->withStringBody(json_encode($response, JSON_PRETTY_PRINT));
        
        $this->controller->setResponse($responseObj);
        $this->_outputMessageSafe($template);
    }
}

