<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostic\Internal;

use ErrorException;
use Throwable;

class DebugParser
{
    private $Severities = [
        E_ERROR             => 'Fatal Error',
        E_WARNING           => 'Warning',
        E_PARSE             => 'Parse Error',
        E_NOTICE            => 'Notice',
        E_CORE_ERROR        => 'Core Error',
        E_CORE_WARNING      => 'Core Warning',
        E_COMPILE_ERROR     => 'Compile Error',
        E_COMPILE_WARNING   => 'Compile Warning',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Strict Error',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED        => 'Deprecated',
        E_USER_DEPRECATED   => 'User Deprecated'
    ];

    public function parse(Throwable $exception) : array
    {
        $trace = $exception->getTrace();
        if ($exception instanceof ErrorException)
        {
            $severity = $this->Severities[$exception->getSeverity()];
        }
        else
        {
            $severity = $this->Severities[E_ERROR];
        }

        $firstfile = $trace[0]['file'] ?? null;

        if ($exception->getFile() == $firstfile)
        {
            array_shift($trace);
        }

        if ($exception->getCode() == 0)
        {
            $code = '';
        }
        else
        {
            $code = $exception->getCode();
        }

        $data['error']   = $severity;
        $data['message'] = $exception->getMessage();
        $data['class']   = get_class($exception);
        $data['code']    = $code;
        $data['file']    = $exception->getFile();
        $data['line']    = $exception->getLine();
        $data['trace']   = $trace;

        return $data;
    }
}
