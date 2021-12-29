<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

class Stopwatch
{
    private float $Elapsed = 0;
    private float $StartTimeStamp = 0;
    private bool $IsRunning = false;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function start()
    {
        if (!$this->IsRunning) {
            $this->StartTimeStamp = microtime(true);
            $this->IsRunning = true;
        }
    }

    public function stop()
    {
        if ($this->IsRunning) {
            $this->Elapsed += microtime(true) - $this->StartTimeStamp;
            $this->IsRunning = false;
        }
    }

    public function reset()
    {
        $this->stop();
        $this->Elapsed = 0;
    }
}
