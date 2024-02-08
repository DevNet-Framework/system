<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

class StackTrace
{
    private array $frames;

    public function __construct(int $skipFrames = 0, array $trace = [])
    {
        if (!$trace) {
            $trace = debug_backtrace();
        }

        for ($i = 0; $i < $skipFrames; $i++) {
            array_shift($trace);
        }

        foreach ($trace as $frame) {
            $this->frames[] = new StackFrame($frame);
        }
    }

    public function getFrame(int $index): ?StackFrame
    {
        return $this->frames[$index] ?? null;
    }

    public function getFrames(): array
    {
        return $this->frames;
    }
}
