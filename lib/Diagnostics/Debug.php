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

class Debug extends Trace
{
    private int $FrameLevel = 1;

    public function _get(string $name)
    {
        return $this->$name;
    }

    public function __construct()
    {
        $this->Listeners = new TraceListenerCollection();
        $this->Listeners->add(new DefaultTraceListener());
    }

    public function assert(bool $condition, string $message): void
    {
        if (!$condition) {
            $this->FrameLevel++;
            $this->fail($message);
        }
    }

    public function fail(string $message): void
    {
        $this->FrameLevel++;
        $this->log($message, 'Assertion Failed');
        exit;
    }

    public function log($value, ?string $category = null): void
    {
        $time = DateTime::createFromFormat('U.u', microtime(TRUE));
        $this->write('[' . $time->format('H:i:s.v') . '] ');
        $this->writeLine($value, $category);
        $this->indent();
        $this->write('at ');
        $this->caller($this->FrameLevel);
        $this->unindent();
    }
}
