<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging\File;

use DateTime;
use DevNet\System\Diagnostics\Trace;
use DevNet\System\Diagnostics\WriterTraceListener;
use DevNet\System\IO\FileAccess;
use DevNet\System\IO\FileMode;
use DevNet\System\IO\FileStream;
use DevNet\System\Logging\ILogger;
use DevNet\System\Logging\LogLevel;

class FileLogger implements ILogger
{
    private string $category;
    private Trace $trace;

    public function __construct(string $category, string $fileName)
    {
        $this->category = $category;
        $this->trace = new Trace();
        $this->trace->Listeners->add(new WriterTraceListener(new FileStream($fileName, FileMode::Open, FileAccess::ReadWrite)));
    }

    public function log(LogLevel $level, string $message, array $args = []): void
    {
        switch ($level) {
            case LogLevel::Trace:
                $severity = 'Trace: ';
                break;
            case LogLevel::Debug:
                $severity = 'Debug: ';
                break;
            case LogLevel::Information:
                $severity = 'Info : ';
                break;
            case LogLevel::Warning:
                $severity = 'Warn : ';
                break;
            case LogLevel::Error:
                $severity = 'Error: ';
                break;
            case LogLevel::Fatal:
                $severity = 'Fatal: ';
                break;
            default:
                return;
                break;
        }

        $category = '';
        if ($this->category) {
            $category = $this->category . ': ';
        }

        $replace = [];
        foreach ($args as $key => $value) {
            // map the arguments if the value can be casted to string
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $replace['{' . $key . '}'] = $value;
            }
        }

        // Interpolate replacement values into the string format
        $message = strtr($message, $replace);

        $this->trace->writeLine($severity . $category . $message);
    }
}
