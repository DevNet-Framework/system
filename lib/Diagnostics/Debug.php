<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DateTime;
use DevNet\System\IO\Console;
use DevNet\System\IO\ConsoleColor;

class Debug
{
    private Trace $Trace;

    public function _get(string $name)
    {
        return $this->$name;
    }

    public function __construct()
    {
        $this->Trace = new Trace();
        $this->Trace->Listeners->add(new DefaultTraceListener());
    }

    public function log($value, ?string $category = null): void
    {
        $time = DateTime::createFromFormat('U.u', microtime(TRUE));
        $this->Trace->write('[' . $time->format('H:i:s.v') . '] ');
        $this->Trace->writeLine($value, $category);
    }

    public function warning(string $message): void
    {
        Console::foregroundColor(ConsoleColor::Yellow);
        $time = DateTime::createFromFormat('U.u', microtime(TRUE));
        $this->Trace->write('[' . $time->format('H:i:s.v') . '] ');
        $this->Trace->writeLine($message, 'Wraning');
        Console::resetColor();
    }

    public function error(string $message): void
    {
        Console::foregroundColor(ConsoleColor::Red);
        $time = DateTime::createFromFormat('U.u', microtime(TRUE));
        $this->Trace->write('[' . $time->format('H:i:s.v') . '] ');
        $this->Trace->writeLine($message, 'Error');
        Console::resetColor();
    }

    public function assert(bool $condition, string $message): void
    {
        if (!$condition) {
            Console::foregroundColor(ConsoleColor::Red);
            $time = DateTime::createFromFormat('U.u', microtime(TRUE));
            $this->Trace->write('[' . $time->format('H:i:s.v') . '] ');
            $this->Trace->writeLine($message, 'Assertion Failed');
            Console::resetColor();

            if (PHP_SAPI == 'cli') {
                exit;
            } else {
                throw new \Exception("Assertion Failed: {$message}");
            }
        }
    }
}
