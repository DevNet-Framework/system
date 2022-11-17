<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DateTime;

class Debug extends Trace
{
    private static ?Debug $instance = null;
    private int $frameLevel = 1;

    public function __construct()
    {
        $this->listeners = new TraceListenerCollection();
        $this->listeners->add(new DefaultTraceListener());
    }

    public function assert(bool $condition, string $message): void
    {
        if (!$condition) {
            $this->frameLevel++;
            $this->fail($message);
        }
    }

    public function fail(string $message): void
    {
        $this->frameLevel++;
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
        $this->caller($this->frameLevel);
        $this->unindent();
    }

    public static function getInstance(): Debug
    {
        if (!self::$instance) {
            self::$instance = new Debug();
        }
        
        return self::$instance;
    }
}
