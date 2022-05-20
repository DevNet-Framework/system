<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DevNet\System\Exceptions\PropertyException;

class Stopwatch
{
    private float $elapsed = 0;
    private float $startTimeStamp = 0;
    private bool $isRunning = false;

    public function __get(string $name)
    {
        if ($name == 'Elapsed') {
            return $this->elapsed;
        }

        if ($name == 'IsRunning') {
            return $this->isRunning;
        }
        
        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function start()
    {
        if (!$this->isRunning) {
            $this->startTimeStamp = microtime(true);
            $this->isRunning = true;
        }
    }

    public function stop()
    {
        if ($this->isRunning) {
            $this->elapsed += microtime(true) - $this->startTimeStamp;
            $this->isRunning = false;
        }
    }

    public function reset()
    {
        $this->stop();
        $this->elapsed = 0;
    }
}
