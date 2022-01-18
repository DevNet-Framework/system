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
    private int $FrameLevel = 0;

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
        $this->Trace->indent();
        $this->Trace->write('at ');
        $this->Trace->caller(1);
        $this->Trace->unindent();
    }

    public function notice(string $message): void
    {
        $report = error_reporting();
        if (
            $report == E_ALL ||
            $report == E_NOTICE ||
            $report == (E_NOTICE | E_WARNING) ||
            $report == (E_NOTICE | E_ERROR)
        ) {
            Console::foregroundColor(ConsoleColor::Green);
            $time = DateTime::createFromFormat('U.u', microtime(TRUE));
            $this->Trace->write('[' . $time->format('H:i:s.v') . '] ');
            $this->Trace->writeLine($message, 'Notice');
            Console::foregroundColor(ConsoleColor::Green);
            $this->Trace->indent();
            $this->Trace->write('at ');
            $this->Trace->caller(1);
            $this->Trace->unindent();
            Console::resetColor();
        }
    }

    public function warning(string $message): void
    {
        $report = error_reporting();
        if (
            $report == E_ALL ||
            $report == E_WARNING ||
            $report == (E_WARNING | E_NOTICE) ||
            $report == (E_WARNING | E_ERROR)
        ) {
            Console::foregroundColor(ConsoleColor::Yellow);
            $time = DateTime::createFromFormat('U.u', microtime(TRUE));
            $this->Trace->write('[' . $time->format('H:i:s.v') . '] ');
            $this->Trace->writeLine($message, 'Wraning');
            Console::foregroundColor(ConsoleColor::Yellow);
            $this->Trace->indent();
            $this->Trace->write('at ');
            $this->Trace->caller(1);
            $this->Trace->unindent();
            Console::resetColor();
        }
    }

    public function error(string $message): void
    {
        $report = error_reporting();
        if (
            $report == E_ALL ||
            $report == E_ERROR ||
            $report == (E_ERROR | E_NOTICE) ||
            $report == (E_ERROR | E_WARNING)
        ) {
            Console::foregroundColor(ConsoleColor::Red);
            $time = DateTime::createFromFormat('U.u', microtime(TRUE));
            $this->Trace->write('[' . $time->format('H:i:s.v') . '] ');
            $this->Trace->writeLine($message, 'Error');
            Console::foregroundColor(ConsoleColor::Red);
            $this->Trace->indent();
            $this->Trace->write('at ');
            $this->Trace->caller(1);
            $this->Trace->unindent();
            Console::resetColor();
        }
    }

    public function assert(bool $condition, string $message): void
    {
        if (!$condition) {
            Console::foregroundColor(ConsoleColor::Red);
            $time = DateTime::createFromFormat('U.u', microtime(TRUE));
            $this->Trace->write('[' . $time->format('H:i:s.v') . '] ');
            $this->Trace->writeLine($message, 'Assertion Failed');
            Console::foregroundColor(ConsoleColor::Red);
            $this->Trace->indent();
            $this->Trace->write('at ');
            $this->Trace->caller(1);
            $this->Trace->unindent();
            Console::resetColor();
        }
    }
}
