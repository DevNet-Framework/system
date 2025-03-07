<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

class Stopwatch
{
    private float $elapsed = 0;
    private float $startTimeStamp = 0;
    private bool $isRunning = false;

    public float $Elapsed { get => $this->elapsed; }
    public bool $IsRunning { get => $this->isRunning; }

    public function start(): void
    {
        if (!$this->isRunning) {
            $this->startTimeStamp = microtime(true);
            $this->isRunning = true;
        }
    }

    public function stop(): void
    {
        if ($this->isRunning) {
            $this->elapsed += microtime(true) - $this->startTimeStamp;
            $this->isRunning = false;
        }
    }

    public function reset(): void
    {
        $this->stop();
        $this->elapsed = 0;
    }
}
