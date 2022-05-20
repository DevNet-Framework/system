<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Diagnostics;

use DevNet\System\Exceptions\PropertyException;

class Trace
{
    protected TraceListenerCollection $Listeners;
    protected int $IndentSize = 4;

    public function __get(string $name)
    {
        if ($name == 'Listeners') {
            return $this->Listeners;
        }

        if ($name == 'IndentSize') {
            return $this->IndentSize;
        }
        
        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __set(string $name, $value)
    {
        if ($name == 'IndentSize') {
            $this->IndentSize = $value;
            foreach ($this->Listeners as $listener) {
                $listener->IndentSize = $value;
            }
            return;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct()
    {
        $this->Listeners = new TraceListenerCollection();
    }

    public function indent(): void
    {
        foreach ($this->Listeners as $listener) {
            $listener->indent();
        }
    }

    public function unindent(): void
    {
        foreach ($this->Listeners as $listener) {
            $listener->unindent();
        }
    }

    public function write($value, ?string $category = null): void
    {
        foreach ($this->Listeners as $listener) {
            $listener->write($value, $category);
        }
    }

    public function writeLine($value, ?string $category = null): void
    {
        foreach ($this->Listeners as $listener) {
            $listener->writeLine($value, $category);
        }
    }

    public function writeIf(bool $condition, $value, ?string $category = null)
    {
        if ($condition) {
            $this->write($value, $category);
        }
    }

    public function writeLineIf(bool $condition, $value, ?string $category = null)
    {
        if ($condition) {
            $this->writeLine($value, $category);
        }
    }

    public function caller(int $skipFrames = 0)
    {
        // adapt the frame level to the outer scope by one step.
        $skipFrames++;
        foreach ($this->Listeners as $listener) {
            $listener->caller($skipFrames);
        }
    }

    public function flush()
    {
        foreach ($this->Listeners as $listener) {
            $listener->flush();
        }
    }
}
