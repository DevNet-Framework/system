<?php
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging\File;

use DateTime;
use DevNet\System\Diagnostics\Trace;
use DevNet\System\Diagnostics\WriterTraceListener;
use DevNet\System\IO\FileStream;
use DevNet\System\Logging\ILogger;
use DevNet\System\Logging\LogLevel;

class FileLogger implements ILogger
{
    private string $Category;
    private Trace $Trace;
    
    public function __construct(string $category, string $fileName)
    {
        $this->Category = $category;
        $this->Trace = new Trace();
        $this->Trace->Providers->add(new WriterTraceListener(new FileStream($fileName, 'a')));
    }

    public function log(int $level, string $message, array $args = []): void
    {
        switch ($level) {
            case LogLevel::Trace:
                $severity = 'Trace: ';
                break;
            case LogLevel::Debug:
                $severity = 'Debug: ';
                break;
            case LogLevel::Information:
                $severity = 'Info: ';
                break;
            case LogLevel::Warning:
                $severity = 'Warn: ';
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

        $dateTime = DateTime::createFromFormat('U.u', microtime(TRUE));
        $date = '[' . $dateTime->format('Y-M-d H:i:s.v') . '] ';

        $replace = [];
        foreach ($args as $key => $value) {
            // map the arguments if the value can be casted to string
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $replace['{' . $key . '}'] = $value;
            }
        }

        // interpolate replacement values into the string format
        $message = strtr($message, $replace);

        $this->Trace->writeLine($date . $this->Category);
        $this->Trace->writeLine('    '. $severity. $message);
    }
}
