<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging\Console;

use DevNet\System\IO\Console;
use DevNet\System\IO\ConsoleColor;
use DevNet\System\Logging\ILogger;
use DevNet\System\Logging\LogLevel;

class ConsoleLogger implements ILogger
{
    private string $category;

    public function __construct(string $category)
    {
        $this->category = $category;
    }

    public function log(LogLevel $level, string $message, array $args = []): void
    {
        switch ($level) {
            case LogLevel::Trace:
                $severity = 'Trace: ';
                Console::$ForegroundColor = ConsoleColor::Blue;
                break;
            case LogLevel::Debug:
                $severity = 'Debug: ';
                Console::$ForegroundColor = ConsoleColor::Cyan;
                break;
            case LogLevel::Information:
                $severity = 'Info : ';
                Console::$ForegroundColor = ConsoleColor::Green;
                break;
            case LogLevel::Warning:
                $severity = 'Warn : ';
                Console::$ForegroundColor = ConsoleColor::Yellow;
                break;
            case LogLevel::Error:
                $severity = 'Error: ';
                Console::$ForegroundColor = ConsoleColor::Red;
                break;
            case LogLevel::Fatal:
                Console::$ForegroundColor = ConsoleColor::White;
                Console::$BackgroundColor = ConsoleColor::Red;
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

        Console::writeLine($severity . $category . $message);
        Console::resetColor();
    }
}
