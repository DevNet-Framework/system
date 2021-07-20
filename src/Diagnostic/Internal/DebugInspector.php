<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostic\Internal;

use DevNet\System\Diagnostic\IDebugHandler;
use ErrorException;
use Throwable;

class DebugInspector
{
    private bool $IsHandled;

    public function __construct()
    {
        error_reporting(E_ALL);
        //ini_set("display_errors", "off");
    }

    public function register(IDebugHandler $handler)
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$handler, 'handle']);
        //register_shutdown_function([$this, 'handleFatalError']);
    }

    /**
     * cach the runtime error and convert it into exception.
     */
    public function handleError(int $severity, string $message, string $file, int $line)
    {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    public function handleFatalError()
    {
        $error = error_get_last();
        if ($error["type"] === E_ERROR) {
            $this->handleError($error["type"], $error["message"], $error["file"], $error["line"]);
        }
    }

    /**
     * default exception handling.
     */
    /* public function handleException(Throwable $exception)
    {
        if ($this->reporter) {
            $this->reporter->report($exception);
        } else {
            $code = $exception->getCode();
            $message = $exception->getMessage();

            if ($code == 0) {
                $code = 500;
            }
            
            header("HTTP/1.0 $code $message");
        }
    } */
}
