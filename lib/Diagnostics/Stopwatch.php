<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DevNet\System\PropertyTrait;

class Stopwatch
{
    use PropertyTrait;

    private float $elapsed = 0;
    private float $startTimeStamp = 0;
    private bool $isRunning = false;

    public function get_Elapsed(): float
    {
        return $this->elapsed;
    }

    public function get_IsRunning(): bool
    {
        return $this->isRunning;
    }

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
