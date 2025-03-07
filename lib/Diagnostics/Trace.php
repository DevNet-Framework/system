<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

class Trace
{
    protected TraceListenerCollection $listeners;
    protected int $indentSize = 4;

    public TraceListenerCollection $Listeners { get => $this->listeners; }
    public int $IndentSize {
        get => $this->indentSize;
        set {
            $this->indentSize = $value;
            foreach ($this->listeners as $listener) {
                $listener->IndentSize = $value;
            }
        }
    }

    public function __construct()
    {
        $this->listeners = new TraceListenerCollection();
    }

    public function indent(): void
    {
        foreach ($this->listeners as $listener) {
            $listener->indent();
        }
    }

    public function unindent(): void
    {
        foreach ($this->listeners as $listener) {
            $listener->unindent();
        }
    }

    public function write($value, ?string $category = null): void
    {
        foreach ($this->listeners as $listener) {
            $listener->write($value, $category);
        }
    }

    public function writeLine($value, ?string $category = null): void
    {
        foreach ($this->listeners as $listener) {
            $listener->writeLine($value, $category);
        }
    }

    public function writeIf(bool $condition, $value, ?string $category = null): void
    {
        if ($condition) {
            $this->write($value, $category);
        }
    }

    public function writeLineIf(bool $condition, $value, ?string $category = null): void
    {
        if ($condition) {
            $this->writeLine($value, $category);
        }
    }

    public function caller(int $skipFrames = 0): void
    {
        // adapt the frame level to the outer scope by one step.
        $skipFrames++;
        foreach ($this->listeners as $listener) {
            $listener->caller($skipFrames);
        }
    }

    public function flush(): void
    {
        foreach ($this->listeners as $listener) {
            $listener->flush();
        }
    }
}
