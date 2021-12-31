<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

class StackTrace
{
    private array $Frames;

    public function __construct(int $skipFrames = 0, array $trace = [])
    {
        if (!$trace) {
            $trace = debug_backtrace();
        }

        for ($i = 0; $i < $skipFrames; $i++) {
            array_shift($trace);
        }

        foreach ($trace as $frame) {
            $this->Frames[] = new StackFrame($frame);
        }
    }

    public function getFrame(int $index): ?StackFrame
    {
        return $this->Frames[$index] ?? null;
    }

    public function getFrames(): array
    {
        return $this->Frames;
    }
}
